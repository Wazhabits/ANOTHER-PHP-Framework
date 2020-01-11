<?php


namespace Core\Connection\Mysql;

use Core\Database\Connection\Mysql\Type\Select;
use Core\Database\Connection\Mysql\Type\Update;

class QueryBuilder
{
    /**
     * @param string|array $fields
     * @return Select
     */
    public function select($fields) {
        return new Select($fields);
    }

    /**
     * @param array $tablename
     * @return Update
     */
    public function update($tablename) {
        return new Update($tablename);
    }
}