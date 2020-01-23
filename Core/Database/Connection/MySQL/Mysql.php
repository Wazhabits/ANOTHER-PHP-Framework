<?php


namespace Core\Connection;

use Core\Connection\Mysql\QueryBuilder;
use Core\Database\Connection;
use Core\Logger;

/**
 * Class Mysql
 * @package Core\Connection
 */
class Mysql implements Connection
{
    /**
     * @var string
     */
    private $name = "mysql";
    /**
     * @var null|mixed
     */
    private $modelReader = null;
    /**
     * @var \PDO|null
     */
    private $pdo = null;
    /**
     * @param $identity
     */
    public function __construct($identity) {
        try {
            $this->pdo = new \PDO(
                'mysql:host=' . $identity["host"] . ':' . $identity["port"] .  ';dbname=' . $identity["name"],
                $identity["user"],
                $identity["pass"],
                [
                    \PDO::ATTR_PERSISTENT => true
                ]
            );
        } catch (\PDOException $exception) {
            Logger::log("database", "Connection error: " . $exception->getMessage(), Logger::$ERROR_LEVEL);
            //TODO: Create an exception thrower
        }
    }
    /**
     * @param string $query
     * @return false|mixed|\PDOStatement
     */
    public function exec($query = "") {
        return $this->pdo->query($query);
    }
    /**
     * @param string $tablename
     * @return QueryBuilder|mixed
     */
    public function getQueryBuilder($tablename = "") {
        return new QueryBuilder($tablename);
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param $reader
     * @return mixed|void
     */
    public function setModelReader(&$reader)
    {
        $this->modelReader = $reader;
    }
}