<?php


namespace Framework\Event;


class KernelBootEvent
{
    /**
     * @event core/kernel.boot
     */
    static function sayHello()
    {
        if (isset($_GET["doKernelBootEvent"]) && (int)$_GET["doKernelBootEvent"] === 1) {
            echo "Hello from boot kernel";
        }
    }
}