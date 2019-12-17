<?php


namespace Core;

use Core\Routing\Routing as Base;

class Routing implements Base
{
    private $current = [];
    private $routes = [];

    public function __construct() {
        foreach (Files::$ROUTING as $route)
            $this->read($route);
    }

    public function read($path) {
        $routes = json_decode(Files::read($path));
        foreach ($routes as $site => $configuration) {
            foreach ($configuration as $route => $controller) {
                $this->routes[$site][$route] = $controller;
            }
        }
    }

    public function setCurrent() {
        $site = $_SERVER["HTTP_HOST"];
        $uri = $_SERVER["REQUEST_URI"];
        if (!isset($this->routes[$site])) {
            return 500;
        } else {
            if (isset($this->routes[$site][$uri])){
                $this->current["route"] = $uri;
                $this->current["controller"] = $this->routes[$site][$uri];
            } else {
                if (!$this->checkRouteParams($site, $uri))
                    return 404;
            }
        }
        return 200;
    }

    public function isURIParameter($SubURIElement) {
        return (substr($SubURIElement, 0, 1) === "[" && substr($SubURIElement, strlen($SubURIElement) - 1, 1) === "]");
    }

    public function checkRouteParams($site, $uri) {
        foreach ($this->routes[$site] as $route => $controller) {
            $existingRouteArray = array_values(array_filter(explode("/", $route)));
            $testingRouteArray = array_values(array_filter(explode("/", $uri)));
            $index = 0;
            if (count($testingRouteArray) === count($existingRouteArray)) {
                while ($index < count($testingRouteArray)) {
                    if (($testingRouteArray[$index] === $existingRouteArray[$index]) || ($testingRouteArray[$index] !== $existingRouteArray[$index] && $this->isURIParameter($existingRouteArray[$index]))) {
                        $index++;
                        if ($index === count($testingRouteArray)) {
                            $this->current["route"] = $route;
                            $this->current["controller"] = $controller;
                            return true;
                        }
                    }
                    else
                        $index = count($testingRouteArray);
                }
            }
        }
        return false;
    }
}