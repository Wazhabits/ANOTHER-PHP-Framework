<?php


namespace Modules\Documentation\Event;

use Core\Database;
use Core\Kernel;
use Core\Logger;
use Modules\Documentation\Model\Classes;
use Modules\Documentation\Model\Method;
use Modules\Documentation\Model\Property;

class KernelBootEvent
{
    /**
     * @event core/kernel.boot
     */
    static function makeDocumentation() {
        $classes = Kernel::getAnnotation()->getDocumentation();
        Logger::log("general", "Loaded classes : " . count($classes));
        foreach ($classes as $class => $properties) {
            $exist = Database\Manager::getConnection("mysql")->getQueryBuilder(Classes::class)->select("*")->from(Classes::class)->where([["classname", "=", $class]])->limit(1)->execute();
            if (!isset($exist[0])) {
                Logger::log("general", "New class detected : " . $class);
                $model = new Classes(["classname" => $class, "json" => json_encode($properties)], 0);
                $model->save();
                $classId = Database\Manager::getConnection("mysql")->getQueryBuilder(Classes::class)->select("*")->from(Classes::class)->where([["classname", "=", $class]])->limit(1)->execute()[0];
                if (isset($properties["properties"])) {
                    $propertiesTemp = $properties["properties"];
                    foreach ($propertiesTemp as $property => $value) {
                        $submodel = new Property(["name" => $property, "configuration" => json_encode($value), "class" => $classId->id]);
                        $submodel->save();
                    }
                }
                $methodTemp = $properties;
                unset($methodTemp["properties"]);
                foreach ($methodTemp as $property => $value) {
                    $submodel = new Method(["name" => $property, "configuration" => json_encode($value), "class" => $classId->id]);
                    $submodel->save();
                }
            }
        }
    }
}