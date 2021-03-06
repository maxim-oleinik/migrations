<?php namespace Blade\Migrations;

use Psr\Log\LoggerInterface;
use Blade\Migrations\Repository\DbRepository;
use Blade\Migrations\Repository\FileRepository;

/**
 * @see \Test\Blade\Migrations\MigrateStatusTest
 * @see \Test\Blade\Migrations\MigrateUpDownTest
 */
class MigrationService implements \Psr\Log\LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @var DbRepository
     */
    private $dbRepository;


    /**
     * Constructor
     *
     * @param FileRepository $fileRepository
     * @param DbRepository   $dbRepository
     */
    public function __construct(FileRepository $fileRepository, DbRepository $dbRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->dbRepository = $dbRepository;
    }


    /**
     * @return DbRepository
     */
    public function getDbRepository(): DbRepository
    {
        return $this->dbRepository;
    }


    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @return Migration[]
     */
    public function status()
    {
        // Найти все файлы миграций
        $files = $this->fileRepository->all();

        $migrations = $this->dbRepository->all();
        $nameIndex = [];
        foreach ($migrations as $migration) {
            $nameIndex[$migration->getName()] = $migration;
            if (!isset($files[$migration->getName()])) {
                $migration->isRemove(true);
            }
        }

        foreach ($files as $name => $path) {
            if (!isset($nameIndex[$name])) {
                $migrations[] = new Migration(null, $name, null);
            }
        }
        return $migrations;
    }


    /**
     * @param bool $onlyNew - Показать только Новые Миграции на добавление (на Удаление не показывать)
     * @return Migration[]
     */
    public function getDiff($onlyNew = false)
    {
        $up = [];
        $down = [];
        foreach ($this->status() as $migration) {
            if ($migration->isNew()) {
                $up[] = $migration;
            } elseif (!$onlyNew && $migration->isRemove()) {
                $down[] = $migration;
            }
        }

        return array_merge($down, $up);
    }


    /**
     * UP
     *
     * @param Migration $migration
     * @param bool      $testRollback
     * @throws \Exception
     */
    public function up(Migration $migration, $testRollback = false)
    {
        if (!$migration->isNew()) {
            throw new \InvalidArgumentException(__METHOD__. ': Expected NEW migration');
        }

        // Загрузить SQL
        $this->fileRepository->loadSql($migration);

        $func = function () use ($migration, $testRollback) {
            $upList = $migration->getUp();
            $this->_processMigrationSql($upList);
            if ($testRollback) {
                if ($this->logger) {
                    $this->logger->info('<comment>Rollback</comment>');
                    $this->logger->info('<comment>----------------------------------</comment>');
                }
                $this->_processMigrationSql($migration->getDown());
                if ($this->logger) {
                    $this->logger->info('<comment>----------------------------------</comment>');
                }
                $this->_processMigrationSql($upList);
            }
            $this->getDbRepository()->insert($migration);
        };

        $this->_processMigration($migration, $func);
    }


    /**
     * DOWN
     *
     * @param Migration $migration
     * @param bool      $loadFromFile
     */
    public function down(Migration $migration, $loadFromFile = false)
    {
        if ($loadFromFile) {
            $this->fileRepository->loadSql($migration);
        } else {
            $this->dbRepository->loadSql($migration);
        }

        $this->_processMigration($migration, function () use ($migration) {
            $this->_processMigrationSql($migration->getDown());
            $this->dbRepository->delete($migration);
        });
    }


    /**
     * Выполнить миграцию
     *
     * @param Migration $migration
     * @param callable  $command
     * @throws \Exception
     */
    private function _processMigration(Migration $migration, callable $command)
    {
        if ($migration->isTransaction()) {
            $this->getDbRepository()->getAdapter()->transaction($command);
        } else {
            if ($this->logger) {
                $this->logger->alert('NO TRANSACTION!');
            }
            $command();
        }
    }


    /**
     * Выполнить полученный SQL
     *
     * @param array $sqlList
     */
    private function _processMigrationSql(array $sqlList)
    {
        foreach ($sqlList as $sql) {
            if ($this->logger) {
                $this->logger->info($sql.PHP_EOL);
            }
            $this->getDbRepository()->getAdapter()->execute($sql);
        }
    }
}
