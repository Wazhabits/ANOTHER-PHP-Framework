<?php


namespace Framework\Event;

use Core\Connection\Mysql;
use Core\Database;
use Core\Kernel;
use Core\Logger;
use Framework\Model\Classes;

class KernelBootEvent
{
    /**
     * @event core/template.postProcess
     */
    static function makeDocumentation() {
        $classes = Kernel::getAnnotation()->getDocumentation();
        Logger::log("general", "Loaded classes : " . count($classes));
        foreach ($classes as $class => $property) {
            $exist = Database\Manager::getConnection("mysql")->getQueryBuilder(Classes::class)->select("*")->from(Classes::class)->where([["classname", "=", $class]])->limit(1)->execute();
            if (isset($exist[0])) {
                $model = $exist[0];
            } else {
                $model = new Classes(["classname" => $class, "json" => json_encode($property)], 0);
                Logger::log("general", "New class detected : " . $class);
            }
            $model->save();
        }
    }
}