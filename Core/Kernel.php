<?php


namespace Core;

include_once __DIR__ . "/Loader/Loader.php";

class Kernel
{
    static $loader;

    static function initialize($path) {
        self::$loader = new Loader($path);
    }
}