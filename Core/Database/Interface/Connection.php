<?php


namespace Core\Database;

/**
 * Interface Connection
 * @package Core\Database
 */
interface Connection
{
    /**
     * @param string $query
     * @return mixed
     */
    static function exec($query = "");

    /**
     * @param string $table
     * @return mixed
     */
    static function getQueryBuilder($table = "");
}