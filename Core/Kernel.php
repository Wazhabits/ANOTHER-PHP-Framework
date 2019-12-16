<?php

namespace Core;

class Kernel
{
    /**
     * @var \Core\Env\Env $environment
     */
    static $environment;

    /**
     * @var \Core\Annotation\Annotation $annotation
     */
    static $annotation;

    /**
     * This function define environment
     */
    static function initialize() {
        self::$environment = new Env(PATH_ROOT . ".env");
        self::$annotation = new Annotation();
        Event::create("coucou");
        Logger::log("general", "KERNEL|Initialize", Logger::$DEFAULT_LEVEL);
    }

    /**
     * @return \Core\Env\Env $environment
     */
    static function getEnvironment() {
        return self::$environment;
    }
}