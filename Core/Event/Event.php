<?php

namespace Core;

use Core\Event\Event as Base;

class Event implements Base
{
    static $event = [];

    /**
     * This function read the annotation entry and add event listener if find method containing the @ event marker
     */
    public static function addEventByAnnotation() {
        foreach (Kernel::getAnnotation()->getByMarker("event") as $classes => $methodElement) {
            foreach ($methodElement as $method => $event) {
                self::add($event, $classes . "::" . $method);
            }
        }
    }

    /**
     * This function add listener to an event
     * @param $eventName = "Namespace/class.method"
     * @param $classnameAndMethod : "My\Class->myMethod()"
     */
    public static function add($eventName, $classnameAndMethod = null)
    {
        if (!array_key_exists($eventName, self::$event)) {
            self::$event[$eventName] = [];
            if ($classnameAndMethod !== null)
                self::$event[$eventName][] = $classnameAndMethod;
        } else {
            self::$event[$eventName][] = $classnameAndMethod;
        }
    }

    /**
     * This function execute all listener associated to an event
     * @param $eventName
     * @param $args
     */
    public static function exec($eventName, &$args = null)
    {
        if (array_key_exists($eventName, self::$event) && !empty(self::$event[$eventName])) {
            foreach (self::$event[$eventName] as $listener) {
                $listener($args);
            }
        }
    }
}
