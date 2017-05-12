<?php
namespace ZFMigrate\Model;



use ZFMigrate\Model\Exception\RuntimeException;

abstract class AbstractMigration
{

    /**
     * @var MigrateDbStorage
     */
    private $migrateTable;

    /**
     * @return MigrateDbStorage
     */
    public function getMigrateDbStorage(): MigrateDbStorage
    {
        return $this->migrateTable;
    }

    /**
     * @param MigrateDbStorage $migrateTable
     */
    public function setMigrateDbStorage(MigrateDbStorage $migrateTable)
    {
        $this->migrateTable = $migrateTable;
    }

    protected function getModuleName() {
        $namespace = explode('\\', get_class($this));
        return $namespace[0];
    }

    /**
     * @param string $file
     * @return string
     */
    protected function loadFile($file)
    {

        $sqlDir = getcwd() . '/module/' . $this->getModuleName() . '/migrations';
        $file = $sqlDir . '/' . $file;

        if (!file_exists($file)) {
            throw new RuntimeException('File not found ' . $file);
        }

        return file_get_contents($file);
    }

    /**
     * @param \Closure $closure
     */
    protected function migrate(\Closure $closure)
    {
        $this->migrateTable->migrate($this->getModuleName(), $closure);
    }

    abstract public function process();
}