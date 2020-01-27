<?php

namespace Core\Database;

use Core\Event;

class Manager
{
    /**
     * @var Connection
     */
    private static $connection = [];

    /**
     * @param string $driver
     * @return Connection|false
     */
    static function getConnection($driver) {
        return (isset(self::$connection[$driver])) ? self::$connection[$driver] : false;
    }

    /**
     * @param Connection &$connection
     */
    static function setConnection(&$connection) {
        self::$connection[$connection->getName()] = $connection;
        Event::exec("core/connection.set", $connection);
    }
}