<?php

namespace Core;


class Kernel
{

    static function initialize() {
        Event::create("coucou");
        Logger::log("general", "[KERNEL]::Initialize", Logger::$DEFAULT_LEVEL);
    }
}