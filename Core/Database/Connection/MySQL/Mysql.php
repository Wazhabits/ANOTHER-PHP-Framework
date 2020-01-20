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
     * @var \PDO|null
     */
    private static $pdo = null;
    /**
     * @var \PDOStatement
     */
    private static $queryResult;

    /**
     * @var QueryBuilder
     */
    private static $queryBuilder;

    /**
     * @param $identity
     */
    static function define($identity) {
        try {
            self::$pdo = new \PDO(
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
    static function exec($query = "") {
        return self::$pdo->query($query);
    }

    /**
     * @param string $tablename
     * @return QueryBuilder|mixed
     */
    static function getQueryBuilder($tablename = "") {
        return new QueryBuilder($tablename);
    }
}