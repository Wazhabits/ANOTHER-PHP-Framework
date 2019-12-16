<?php

namespace Core;


class Kernel
{
    static $environment;

    static function initialize() {
        self::$environment = new Env(ROOT_DIRECTORY . ".env");
        Event::create("coucou");
        Logger::log("general", "[KERNEL]::Initialize", Logger::$DEFAULT_LEVEL);
    }
}