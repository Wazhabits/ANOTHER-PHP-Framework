<?php

namespace Core;

class Kernel
{
    /**
     * @var \Core\Env\Env $environment
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
        self::$environment = new Env(PATH_ROOT . ".env");
        self::$environment->set("time", "Load of class & Define env:" .($classTime = self::$environment->getExecutionTime()). "ms", true);
        self::$annotation = new Annotation();
        self::$environment->set("time", "AnnotationInit:" . self::$environment->getExecutionTime(). "ms", true);
        self::$routing = new Routing();
        self::$environment->set("time", "RoutingInit:" . self::$environment->getExecutionTime(). "ms", true);
        self::$context = Kernel::getEnvironment()->getConfiguration("APPLICATION_CONTEXT");
        Event::addEventByAnnotation();
        self::$environment->set("time", "EventInit:" . self::$environment->getExecutionTime(). "ms", true);
        Response::initialize();
        Event::exec("core/kernel.boot", $injection);
        if (is_array($injection))
            self::inject($injection);
        if (self::$routing->getCurrent()["status"] === 200) {
            self::makeControllerCall(self::$routing->getCurrent());
        }
        Response::setHeader(["babti" => "babtibou"]);
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
        return self::$controller->{$controller[1]}();
    }

    /**
     * @return \Core\Env\Env $environment
     */
    static function getEnvironment() {
        return self::$environment;
    }

    /**
     * @return \Core\Annotation\Annotation $annotation
     */
    static function getAnnotation() {
        return self::$annotation;
    }
}