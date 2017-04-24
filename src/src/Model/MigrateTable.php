<?php

namespace ZFMigrate\Model;

use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\ModuleManager;

class MigrateTable
{

    /**
     * @var \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway;

    /**
     * @var array
     */
    protected $version = [];

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @var bool
     */
    protected $silent = false;

    /**
     * @param TableGateway $tableGateway
     * @param ModuleManager $moduleManager
     */
    public function __construct(TableGateway $tableGateway, ModuleManager $moduleManager)
    {
        $this->tableGateway = $tableGateway;
        $this->moduleManager = $moduleManager;
    }

    /**
     * @return int
     */
    public function getVersion($module)
    {
        try {
            $version = $this->tableGateway->select(function (Select $select) use ($module) {
                $select->where(['module' => $module]);
                $select->limit(1);
            })->current();
        } catch (InvalidQueryException $e) {
            return 0;
        }

        if (null == $version) {
            $this->tableGateway->insert(['version' => 0, 'module' => $module]);
            return 0;
        }

        return (int)$version['version'];
    }

    /**
     * @param int $version
     */
    public function setVersion($module, $version)
    {
        $this->tableGateway->update(['version' => $version], ['module' => $module]);
    }

    /**
     * @param $version
     * @param $callback
     */
    public function migrate($module, $callback)
    {
        if (!isset($this->version[$module])) {
            $this->version[$module] = 1;
        }

        $version = $this->version[$module];

        if ($version > $this->getVersion($module)) {

            if (!$this->silent) {
                echo $module . ": Executing Migration " . $version . PHP_EOL;
            }

            $sql = $this->tableGateway->getSql();
            $adapter = $sql->getAdapter();

            $callback($adapter);

            $this->setVersion($module, $version);
        }

        $version++;
        $this->version[$module] = $version;
    }

    /**
     * @param bool $silent
     */
    public function doMigrate($silent = false)
    {
        $loadedModules = $this->moduleManager->getLoadedModules();
        $this->silent = $silent;

        /* @var \Zend\ServiceManager\Di\Module $module */
        foreach ($loadedModules as $module) {
            if ($module instanceof MigrateInterface) {
                $migration = $module->getMigration();
                $migration->setMigrateTable($this);
                $migration->process();
            }
        }
    }

}