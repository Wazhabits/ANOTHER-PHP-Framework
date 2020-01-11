<?php


namespace Core;

use Core\Routing\Routing as Base;

class Routing implements Base
{
    private $current = [];
    private $routes = [];
    private $status;

    public function __construct() {
        foreach (Loader::$ROUTING as $route)
            $this->read($route);
        /**
         * Create routing for controller who had the @ route annotation
         */
        $annotation = Kernel::getAnnotation()->getDocumentation();
        foreach ($annotation as $classname => $conf) {
            foreach ($conf as $method => $configuration) {
                if (isset($configuration) && array_key_exists("route", $configuration)) {
                    if (isset($configuration["site"])) {
                        $site = trim($configuration["site"][0]);
                        foreach ($configuration["route"] as $route) {
                            $route = trim($route);
                            $this->routes[$site][$route] = $classname . "->" . $method;
                        }
                    }
                }
            }
        }
        /**
         * Tell if a site of app doesn't had existing route
         */
        foreach (explode(",",Kernel::getEnvironment()->getConfiguration("SITES_DOMAINS")) as $site) {
            if (!isset($this->routes[$site]) || empty($this->routes[$site]))
                Logger::log("routing", "ROUTING|Site '" . $site . "' has no route", Logger::$WARNING_LEVEL);
        }
        Event::exec("core/routing.read", $this->routes);
        Logger::log("routing", "ROUTING|Read route", Logger::$DEFAULT_LEVEL);
        $this->status = $this->setCurrent();
        Event::exec("core/routing.current", $this->current);
    }

    /**
     * This function read a .routing json file and create associate route into memory
     * @param $path
     */
    private function read($path) {
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
    private function setCurrent() {
        $site = $_SERVER["HTTP_HOST"];
        $uri = $_SERVER["REQUEST_URI"];
        if (!isset($this->routes[$site])) {
            Event::exec("core/routing.500", $this->routes);
            Logger::log("routing", "ROUTING|Internal Error, code 500", Logger::$ERROR_LEVEL);
            return 500;
        } else {
            if (isset($this->routes[$site][$uri])){
                $this->current["route"] = $uri;
                $this->current["controller"] = $this->routes[$site][$uri];
                $this->current["site"] = $site;
            } else {
                if (!$this->checkRouteParams($site, $uri)) {
                    Event::exec("core/routing.404", $this->routes);
                    Logger::log("routing", "ROUTING|Not Found, code 404", Logger::$ERROR_LEVEL);
                    return 404;
                }
            }
        }
        Event::exec("core/routing.200", $this->routes);
        Logger::log("routing", "ROUTING|OK, code 200", Logger::$DEFAULT_LEVEL);
        return 200;
    }

    /**
     * This function tell if a subelement of URI is a param of route
     * @param $SubURIElement
     * @return bool
     */
    private function isURIParameter($SubURIElement) {
        return (substr($SubURIElement, 0, 1) === "{" && substr($SubURIElement, strlen($SubURIElement) - 1, 1) === "}");
    }

    /**
     * This function compare existing route to the server request route, it can define the current Route
     * @param $site
     * @param $uri
     * @return bool
     */
    private function checkRouteParams($site, $uri) {
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

    /**
     * This function return the current route and the status code
     * @return array
     */
    public function getCurrent() {
        return ["route" => $this->current, "status" => $this->status];
    }
}