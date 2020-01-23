<?php

namespace Core\Database\Connection\MySQL\Event;

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
            if (!isset($injection["mysql"]))
                $injection["mysql"] = new Database();
            if (!isset($injection["mysql"]))
                $injection["mysql"]["reader"] = new Model();
        }
    }
}