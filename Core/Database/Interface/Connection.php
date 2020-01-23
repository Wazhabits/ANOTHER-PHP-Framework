<?php


namespace Core\Database;

/**
 * Interface Connection
 * @package Core\Database
 */
interface Connection
{
    /**
     * Execute a query generate by QueryBuilder
     * @param string $query
     * @return mixed
     */
    public function exec($query = "");

    /**
     * Return a QueryBuilder instance
     * @param string $table
     * @return mixed
     */
    public function getQueryBuilder($table = "");

    /**
     * Return driver name
     * @return string
     */
    public function getName();

    /**
     * @param $reader
     * @return mixed
     */
    public function setModelReader(&$reader);
}