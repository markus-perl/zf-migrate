<?php

namespace ZFMigrate;

use ZFMigrate\Controller\DatabaseController;
use ZFMigrate\Model\MigrateInterface;
use ZFMigrate\Model\MigrateDbStorage;
use Laminas;
use Laminas\Console\Adapter\AdapterInterface as Console;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\ConsoleUsageProviderInterface;

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
                MigrateDbStorage::class => function ($sm) {
                    $dbAdapter = $sm->get('Laminas\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $tableGateway = new TableGateway('migrate', $dbAdapter, null, $resultSetPrototype);
                    $table = new MigrateDbStorage($tableGateway, $sm->get('ModuleManager'));
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
                        $container->get(MigrateDbStorage::class)
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
