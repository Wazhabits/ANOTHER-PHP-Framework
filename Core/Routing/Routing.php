<?php


namespace Core;

use Core\Routing\Routing as Base;

class Routing implements Base
{
    public function __construct() {
        foreach (Files::$ROUTING as $route)
            $this->read($route);
    }

    public function read($path) {
        $routes = json_decode(Files::read($path));
        //var_dump($routes);
    }
}