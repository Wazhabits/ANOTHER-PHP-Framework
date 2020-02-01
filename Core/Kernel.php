<?php

namespace Core;

class Kernel
{
    /**
     * @var \Core\Env\Environment $environment
     */
    static $environment;

    /**
     * @var \Core\Annotation\Annotation $annotation
     */
    static $annotation;

    /**
     * @var \Core\Routing\Routing $routing
     */
    static $routing;

    /**
     * @var
     */
    static $controller;

    /**
     * @var array
     */
    static $injected = [];

    /**
     * @var string $context
     */
    static $context;

    /**
     * This function define environment
     */
    static function boot() {
        Response::initialize();
        Environment::read(PATH_ROOT . ".env");
        Environment::set("time", "Load of class & Define env:" .($classTime = Environment::getExecutionTime()). "ms", true);
        self::$annotation = new Annotation();
        Environment::set("time", "AnnotationInit:" . Environment::getExecutionTime(). "ms", true);
        self::$routing = new Routing();
        Environment::set("time", "RoutingInit:" . Environment::getExecutionTime(). "ms", true);
        self::$context = Environment::getConfiguration("APPLICATION_CONTEXT");
        Event::addEventByAnnotation();
        Environment::set("time", "EventInit:" . Environment::getExecutionTime(). "ms", true);
        $injection = [];
        Event::exec("core/kernel.boot", $injection);
        self::inject($injection);
        if (self::$routing->getCurrent()["status"] === 200)
            self::makeControllerCall(self::$routing->getCurrent());
        Response::send();
    }

    /**
     * Injection from the core/kernel.boot event
     * @param $injection
     */
    static function inject($injection) {
        foreach ($injection as $property => $value) {
            self::$injected[$property] = $value;
        }
    }

    /**
     * Return a service
     * @param $service
     * @return bool|mixed
     */
    static function get($service) {
        if (isset(self::$injected[$service]))
            return self::$injected[$service];
        else
            if (isset(self::$$service))
                return self::$$service;
            else
                return false;
    }

    /**
     * @param $current
     * @return mixed
     */
    static function makeControllerCall($current) {
        $controller = explode("->", $current["route"]["controller"]);
        self::$controller = new $controller[0]();
        Event::exec("core/controller.call", self::$controller);
        return self::$controller->{$controller[1]}($current);
    }

    /**
     * @return \Core\Annotation\Annotation $annotation
     */
    static function getAnnotation() {
        return self::$annotation;
    }
}