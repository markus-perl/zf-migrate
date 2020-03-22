<?php

namespace ZFMigrate\Controller;

use Laminas\ModuleManager\ModuleManager;
use Laminas\Mvc\Controller\AbstractActionController;
use ZFMigrate\Model\MigrateDbStorage;

class DatabaseController extends AbstractActionController
{

    /**
     * @var MigrateDbStorage
     */
    private $table;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    public function __construct(MigrateDbStorage $table)
    {
        $this->table = $table;
    }

    public function migrateAction()
    {
        $this->table->doMigrate();
        echo "Done." . PHP_EOL;
    }

}