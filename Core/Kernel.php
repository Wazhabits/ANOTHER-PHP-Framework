<?php

namespace Core;

class Kernel
{
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
        self::$annotation = new Annotation();
        self::$routing = new Routing();
        self::$context = Environment::getConfiguration("APPLICATION_CONTEXT");
        Event::linkEvent();
        Event::exec("core/kernel.boot", $injection);
        self::inject($injection);
        $result = (self::$routing->getCurrent()["status"] === 200) ? self::makeControllerCall(self::$routing->getCurrent()) : null;
        if ($result !== null) {
            Response::setHeader(["Content-Type" => "application/json"]);
            Response::send();
            echo json_encode($result);
        } else
            Response::send();
    }

    /**
     * Injection from the core/kernel.boot event
     * @param $injection
     */
    static function inject($injection) {
        if (is_array($injection))
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
        return (in_array(\Core\Controller\Controller::class, class_implements(self::$controller))) ? self::$controller->{$controller[1]}($current) : false;
    }

    /**
     * @return \Core\Annotation\Annotation $annotation
     */
    static function getAnnotation() {
        return self::$annotation;
    }
}