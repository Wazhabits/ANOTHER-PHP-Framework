<?php


namespace Core\Database\Connection\Mysql\Type;


class Delete extends BaseType
{
    public function __construct($tablename)
    {
        parent::$configuration = [];
        parent::$configuration["table"]["name"] = $tablename;
        parent::$configuration["table"]["sql"] = "DELETE FROM " . strtolower($tablename);
    }

    /**
     * @return bool|string
     */
    public function getQuery()
    {
        return (!isset(parent::$configuration["where"]["sql"])) ?
            false : parent::$configuration["table"]["sql"] . parent::$configuration["where"]["sql"];
    }
}