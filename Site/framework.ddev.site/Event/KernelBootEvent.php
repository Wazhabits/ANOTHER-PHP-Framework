<?php


namespace Framework\Event;


use Core\Database;

class KernelBootEvent
{
    /**
     * @event core/kernel.boot
     */
    static function sayHello(&$injection)
    {
        if (!isset($_GET["excludeDatabase"]))
            $injection["database"] = new Database();
    }
}