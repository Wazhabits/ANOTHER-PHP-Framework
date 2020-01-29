<?php

namespace Core\Database\Connection\MySQL\Event;

use Core\Connection\Mysql;
use Core\Kernel;
use Core\Database;
use Core\Database\Connection\MySQL\Reader\Model;

class KernelBootEvent
{
    /**
     * @event core/kernel.boot
     */
    static function listenKernelBoot() {
        if (Kernel::getEnvironment()->getConfiguration("DATABASE_ENABLE") === "true" && Kernel::getEnvironment()->getConfiguration("DATABASE_DRIVER") === "mysql") {
            $connection = new Mysql([
                "host" => Kernel::getEnvironment()->getConfiguration("MYSQL_HOST"),
                "port" => Kernel::getEnvironment()->getConfiguration("MYSQL_PORT"),
                "name" => Kernel::getEnvironment()->getConfiguration("MYSQL_NAME"),
                "user" => Kernel::getEnvironment()->getConfiguration("MYSQL_USER"),
                "pass" => Kernel::getEnvironment()->getConfiguration("MYSQL_PASS")
            ]);
            Database\Manager::setConnection($connection);
        }
    }

    /**
     * @param Database\Connection $connection
     * @event core/connection.set
     */
    static function listenConnectionSet(&$connection) {
        $modelReader = new Model();
        $connection->setModelReader($modelReader);
        foreach (Database\Manager::getScheme() as $tablename => $schemes) {
            if (isset($schemes["sql"]))
                Database\Manager::getConnection("mysql")->exec($schemes["sql"]);
        }
    }
}