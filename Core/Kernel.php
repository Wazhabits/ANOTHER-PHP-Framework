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
        self::$annotation = new Annotation();
        self::$routing = new Routing();
        Logger::log("general", "KERNEL|Initialize", Logger::$DEFAULT_LEVEL);
        Event::addEventByAnnotation();
        Event::exec("core/kernel.boot");
        if (self::$routing->getCurrent()["status"] === 200) {
            self::makeControllerCall(self::$routing->getCurrent());
        }
        http_response_code(self::$routing->getCurrent()["status"]);
    }

    /**
     * @param $current
     * @return mixed
     */
    static function makeControllerCall($current) {
        $controller = explode("->", $current["route"]["controller"]);
        $controllerClassName = $controller[0];
        $controllerMethod = $controller[1];
        $class = new $controllerClassName();
        return $class->{$controllerMethod}();
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