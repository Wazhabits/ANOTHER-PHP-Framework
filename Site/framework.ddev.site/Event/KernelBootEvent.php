<?php


namespace Framework\Event;


use Core\Database;

class KernelBootEvent
{
    /**
     * @param &array $injection
     * @event core/kernel.boot
     */
    static function connectToDatabase(&$injection)
    {
        if (!isset($_GET["excludeDatabase"]))
            $injection["mysql"] = new Database();
        else
            $injection["mysql"] = new Database("mongodb", []);
    }
}