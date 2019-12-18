<?php


namespace Framework\Event;


class KernelBootEvent
{
    /**
     * @event core/kernel.boot
     */
    static function sayHello()
    {
        var_dump("Hello");
    }
}