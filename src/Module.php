<?php

namespace ZFMigrate;

use ZFMigrate\Controller\DatabaseController;
use ZFMigrate\Model\MigrateInterface;
use ZFMigrate\Model\MigrateTable;
use Zend;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class Module implements ConfigProviderInterface, ConsoleUsageProviderInterface, MigrateInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * This method is defined in ConsoleUsageProviderInterface
     */
    public function getConsoleUsage(Console $console)
    {
        return array(
            'migrate database' => 'migrate database',
        );
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                MigrateTable::class => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $tableGateway = new TableGateway('migrate', $dbAdapter, null, $resultSetPrototype);
                    $table = new MigrateTable($tableGateway, $sm->get('ModuleManager'));
                    return $table;
                }
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                DatabaseController::class => function ($container) {
                    return new DatabaseController(
                        $container->get(MigrateTable::class)
                    );
                },

            ],
        ];
    }

    /**
     * @return Model\AbstractMigration
     */
    public function getMigration()
    {
        return new Migrate();
    }
}
