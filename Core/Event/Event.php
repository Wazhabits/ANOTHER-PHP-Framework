<?php

namespace Core;

use Core\Event\Event as Base;

class Event implements Base
{
    static $event = [];

    public static function addEventByAnnotation() {
        $annotation = Kernel::getAnnotation()->getDocumentation();
        foreach ($annotation as $classes => $configuration) {
            foreach ($configuration as $method => $comment) {
                if ($comment) {
                    if (array_key_exists("event", $comment)) {
                        foreach ($comment["event"] as $listenEvent) {
                            self::add(trim($listenEvent), $classes . "::" . $method);
                        }
                    }
                }
            }
        }
        echo "<pre><code>", var_dump(self::$event), "</pre></code>";
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
