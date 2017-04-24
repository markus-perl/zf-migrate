<?php

namespace ZFMigrate\Controller;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\Controller\AbstractActionController;
use ZFMigrate\Model\MigrateTable;

class DatabaseController extends AbstractActionController
{

    /**
     * @var MigrateTable
     */
    private $table;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    public function __construct(MigrateTable $table)
    {
        $this->table = $table;
    }

    public function migrateAction()
    {
        $this->table->doMigrate();
        echo "Done." . PHP_EOL;
    }

}