<?php

namespace Core;

use Core\Event\Event as Base;

class Event implements Base
{
    static $event = [];

    /**
     * This function add listener to an event
     * @param $eventName = "Namespace/class.method"
     * @param $classnameAndMethod : "My\Class->myMethod()"
     */
    public static function add($eventName, $classnameAndMethod)
    {
        if (!array_key_exists($eventName, self::$event))
            self::$event[$eventName] = [];
        self::$event[$eventName][] = $classnameAndMethod;
    }

    /**
     * This function execute all listener associated to an event
     * @param $eventName
     * @param $args
     */
    public static function exec($eventName, &$args)
    {
        if (array_key_exists($eventName, self::$event) && !empty(self::$event[$eventName])) {
            foreach (self::$event[$eventName] as $listener) {
                $listener($args);
            }
        }
    }
}
