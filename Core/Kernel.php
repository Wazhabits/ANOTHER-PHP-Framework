<?php

namespace Core;

use Core\Event;

class Kernel
{

    static function initialize() {
        Event::create("coucou");
    }
}