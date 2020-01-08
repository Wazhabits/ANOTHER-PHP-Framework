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
        $injection["database"] = new Database();
    }
}