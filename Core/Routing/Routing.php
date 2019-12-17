<?php


namespace Core;

use Core\Routing\Routing as Base;

class Routing implements Base
{
    private $current = [];
    private $routes = [];
    private $status;

    public function __construct() {
        foreach (Files::$ROUTING as $route)
            $this->read($route);
        $this->status = $this->setCurrent();
    }

    /**
     * This function read a .routing json file and create associate route into memory
     * @param $path
     */
    public function read($path) {
        $routes = json_decode(Files::read($path));
        foreach ($routes as $site => $configuration) {
            foreach ($configuration as $route => $controller) {
                $this->routes[$site][$route] = $controller;
            }
        }
    }

    /**
     * This function define which route of which site is used, it return the http response status
     * @return int
     */
    public function setCurrent() {
        $site = $_SERVER["HTTP_HOST"];
        $uri = $_SERVER["REQUEST_URI"];
        if (!isset($this->routes[$site])) {
            return 500;
        } else {
            if (isset($this->routes[$site][$uri])){
                $this->current["route"] = $uri;
                $this->current["controller"] = $this->routes[$site][$uri];
                $this->current["site"] = $site;
            } else {
                if (!$this->checkRouteParams($site, $uri))
                    return 404;
            }
        }
        return 200;
    }

    /**
     * This function tell if a subelement of URI is a param of route
     * @param $SubURIElement
     * @return bool
     */
    public function isURIParameter($SubURIElement) {
        return (substr($SubURIElement, 0, 1) === "[" && substr($SubURIElement, strlen($SubURIElement) - 1, 1) === "]");
    }

    /**
     * This function compare existing route to the server request route, it can define the current Route
     * @param $site
     * @param $uri
     * @return bool
     */
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
                            $this->current["site"] = $site;
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