<?php


namespace Core;

use Core\Routing\Routing as Base;

class Routing implements Base
{
    public function __construct() {

    }

    public function read($path) {
        $routes = json_decode(Files::read($path));
        var_dump($routes);
    }
}