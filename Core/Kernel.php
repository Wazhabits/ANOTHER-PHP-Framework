<?php

namespace Core;

class Kernel
{
    /**
     * @var Env $environment
     */
    static $environment;

    /**
     * This function define environment
     */
    static function initialize() {
        self::$environment = new Env(PATH_ROOT . ".env");
        Event::add("core/kernel.initialize", "coucou");
        Logger::log("general", "KERNEL|Initialize", Logger::$DEFAULT_LEVEL);
    }

    /**
     * @return Env $environment
     */
    static function getEnvironment() {
        return self::$environment;
    }
}