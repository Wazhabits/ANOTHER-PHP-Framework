<?php


namespace Framework\Event;

use Core\Connection\Mysql;
use Core\Database;
use Core\Kernel;
use Framework\Model\Classes;

class KernelBootEvent
{
    /**
     * @param &array $injection
     * @event core/kernel.boot
     */
    static function connectToDatabase(&$injection)
    {
        if (!isset($_GET["excludeDatabase"]))
            $injection["mysql"] = new Database();
        self::makeDocumentation();

    }

    static function makeDocumentation() {
        $classes = Kernel::getAnnotation()->getDocumentation();

        foreach ($classes as $class => $property) {
            $exist = Mysql::getQueryBuilder(Classes::class)->select("*")->from(Classes::class)->where([["classname", "=", $class]])->limit(1)->execute();
            if (isset($exist[0])) {
                $model = $exist[0];
            } else {
                $model = new Classes(["classname" => $class, "json" => json_encode($property)], 0);
            }
            $model->save();
        }
    }
}