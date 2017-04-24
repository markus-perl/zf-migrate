<?php
namespace ZFMigrate\Model;


interface MigrateInterface
{
    /**
     * @return AbstractMigration
     */
    public function getMigration();

}