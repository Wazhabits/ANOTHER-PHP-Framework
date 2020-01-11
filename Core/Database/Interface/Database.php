<?php


namespace Core\Database;

/**
 * Interface Database
 * @package Core\Database
 */
interface Database
{
    /**
     * @return Connection
     */
    public function getConnection();
}