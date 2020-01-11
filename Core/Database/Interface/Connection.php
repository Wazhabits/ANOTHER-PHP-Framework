<?php


namespace Core\Database;

/**
 * Interface Connection
 * @package Core\Database
 */
interface Connection
{
    public function exec($query);
    public function fetchAll();
    public function getQueryBuilder();
}