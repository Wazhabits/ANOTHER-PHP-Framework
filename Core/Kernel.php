<?php


namespace Core;

include_once __DIR__ . "/Loader/Loader.php";

use Event;

class Kernel
{
    static $loader;

    static function initialize($path) {
        self::$loader = new Loader($path);
        Event::creat('test');
    }
}