<?php


namespace Framework\Event;


use Core\Database;

class KernelBootEvent
{
    /**
     * @event core/kernel.boot
     */
    static function connectToDatabase(&$injection)
    {
        if (!isset($_GET["excludeDatabase"]))
            $injection["database"] = new Database();
    }
}