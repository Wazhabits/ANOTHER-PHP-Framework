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
     * This function define environment
     */
    static function boot() {
        self::$environment = new Env(PATH_ROOT . ".env");
        self::$environment->set("time", "Load of class & Define env:" .($classTime = self::$environment->getExecutionTime()). "ms", true);
        self::$annotation = new Annotation();
        self::$environment->set("time", "AnnotationInit:" .($annoTime = self::$environment->getExecutionTime() - $classTime). "ms", true);
        self::$routing = new Routing();
        self::$environment->set("time", "RoutingInit:" .($routingTime = self::$environment->getExecutionTime() - $annoTime). "ms", true);
        Logger::log("general", "KERNEL|Initialize", Logger::$DEFAULT_LEVEL);
        Event::addEventByAnnotation();
        self::$environment->set("time", "EventInit:" .($eventTime = self::$environment->getExecutionTime() - $routingTime). "ms", true);
        Event::exec("core/kernel.boot");
        if (self::$routing->getCurrent()["status"] === 200) {
            self::makeControllerCall(self::$routing->getCurrent());
        }
        http_response_code(self::$routing->getCurrent()["status"]);
        self::$environment->set("time", "ControllerCall:" .($controllerTime = self::$environment->getExecutionTime() - $routingTime). "ms", true);
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
     * @return Annotation\Annotation $annotation
     */
    static function getAnnotation() {
        return self::$annotation;
    }
}