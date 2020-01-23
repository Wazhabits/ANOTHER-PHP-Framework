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
    static function listenKernelBoot(&$injection) {
        if (Kernel::getEnvironment()->getConfiguration("DATABASE_ENABLE") === "true" && Kernel::getEnvironment()->getConfiguration("DATABASE_DRIVER") === "mysql") {
            $connection = new Mysql([
                "host" => Kernel::getEnvironment()->getConfiguration("MYSQL_HOST"),
                "port" => Kernel::getEnvironment()->getConfiguration("MYSQL_PORT"),
                "name" => Kernel::getEnvironment()->getConfiguration("MYSQL_NAME"),
                "user" => Kernel::getEnvironment()->getConfiguration("MYSQL_USER"),
                "pass" => Kernel::getEnvironment()->getConfiguration("MYSQL_PASS")
            ]);
            $modelReader = new Model();
            $connection->setModelReader($modelReader);
            Database\Manager::setConnection($connection);
        }
    }
}