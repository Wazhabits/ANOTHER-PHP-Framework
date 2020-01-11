<?php


namespace Core\Database\Connection\MySQL\Type;


interface QueryBuilder
{
    /**
     * @param $fields
     * @return Select
     */
    public function select($fields);
    /**
     * @param $tablename
     * @return Update
     */
    public function update($tablename);
    /**
     * @param $tablename
     * @return mixed
     */
    public function delete($tablename);
    /**
     * @param $tablename
     * @return mixed
     */
    public function create($tablename);
}