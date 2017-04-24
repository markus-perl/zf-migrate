<?php

namespace ZFMigrate;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;

class Migrate extends \ZFMigrate\Model\AbstractMigration
{

    public function process()
    {
        $this->migrate(function (AdapterInterface $adapter) {
            $adapter->query('CREATE TABLE `migrate` ( `module` VARCHAR(50) NOT NULL,
                                `version` SMALLINT(5) UNSIGNED NOT NULL
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8; ALTER TABLE `migrate`
                              ADD PRIMARY KEY (`module`);
                              INSERT INTO `migrate` (`module`, `version`) VALUES (\'migrate\', \'1\')
                              ', Adapter::QUERY_MODE_EXECUTE);
        });
    }
}