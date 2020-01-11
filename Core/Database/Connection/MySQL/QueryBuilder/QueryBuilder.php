<?php


namespace Core\Connection\Mysql;

use Core\Database\Connection\Mysql\Type\Delete;
use Core\Database\Connection\Mysql\Type\Insert;
use Core\Database\Connection\Mysql\Type\Select;
use Core\Database\Connection\Mysql\Type\Update;
use Core\Database\QueryBuilder as Base;

/**
 * Class QueryBuilder
 * @package Core\Connection\Mysql
 */
class QueryBuilder implements Base
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

    /**
     * @param $tablename
     * @return Insert
     */
    public function insert($tablename)
    {
        return new Insert($tablename);
    }

    /**
     * @param $tablename
     * @return Delete
     */
    public function delete($tablename)
    {
        return new Delete($tablename);
    }
}