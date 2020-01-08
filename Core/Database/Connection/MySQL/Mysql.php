<?php


namespace Core\Connection;

use Core\Connection\Mysql\QueryBuilder;
use Core\Database\Connection;

class Mysql implements Connection
{
    /**
     * @var \PDO|null
     */
    private $pdo = null;
    /**
     * @var \PDOStatement
     */
    private $queryResult;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * Mysql constructor.
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
            $this->queryBuilder = new QueryBuilder();
        } catch (\PDOException $exception) {
            //TODO: Create an exception thrower
            return null;
        }
    }
    /**
     * @param string $query
     * @return $this
     */
    public function exec($query) {
        $this->queryResult =  $this->pdo->query($query);
        return $this;
    }
    /**
     * @return array|bool
     */
    public function fetchAll() {
        if ($this->queryResult)
            return $this->queryResult->fetchAll();
        else
            return false;
    }
    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder() {
        return $this->queryBuilder;
    }
}