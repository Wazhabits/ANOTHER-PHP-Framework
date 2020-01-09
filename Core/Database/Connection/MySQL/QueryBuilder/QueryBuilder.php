<?php


namespace Core\Connection\Mysql;


use Core\Connection\Mysql\QueryType\Select;

class QueryBuilder
{
    /**
     * @param string|array $fields
     * @return Select
     */
    public function select($fields) {
        return new Select($fields);
    }
}