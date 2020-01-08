<?php


namespace Core\Database;


interface Database
{
    /**
     * @return Connection
     */
    public function getConnection();
}