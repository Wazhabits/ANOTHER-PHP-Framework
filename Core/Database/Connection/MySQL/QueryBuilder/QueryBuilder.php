<?php


namespace Core\Connection\Mysql;

use Core\Database\Connection\Mysql\Type\Select;

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