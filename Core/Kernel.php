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
    static function boot() {
        self::$environment = new Env(PATH_ROOT . ".env");
        self::$annotation = new Annotation();
        Event::add("core/kernel.boot");
        Logger::log("general", "KERNEL|Initialize", Logger::$DEFAULT_LEVEL);
    }

    /**
     * @return \Core\Env\Env $environment
     */
    static function getEnvironment() {
        return self::$environment;
    }

    /**
     * @return Annotation\Annotation $annotation
     */
    static function getAnnotation() {
        return self::$annotation;
    }
}