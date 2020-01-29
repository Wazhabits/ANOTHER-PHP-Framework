<?php


namespace Modules\Documentation;


use Core\Database\Manager;
use Core\Kernel;
use Modules\Documentation\Model\Classes;
use Modules\Documentation\Model\Method;
use Modules\Documentation\Model\Property;

class Maker
{
    public function extract() {
        $classes = Kernel::getAnnotation()->getDocumentation();
        foreach ($classes as $className => $configuration) {
            $methods = $configuration;
            if (isset($configuration["properties"]))
                $properties = $configuration["properties"];
            $classInDatabase = Manager::getConnection("mysql")->getQueryBuilder(Classes::class)->select("*")->from(Classes::class)->where([["classname", "=", $className]])->limit(1)->execute();
            unset($methods["properties"]);

            if (count($classInDatabase) === 1 && isset($classInDatabase[0]->id)) {
                $id = $classInDatabase[0]->id;
                if (isset($properties))
                    foreach ($properties as $property => $propertyConfiguration) {
                        $this->checkClassSubElement(Property::class, $property, $id, $configuration);
                    }
                foreach ($methods as $method => $methodConfiguration) {
                    $this->checkClassSubElement(Method::class, $method, $id, $configuration);
                }
            } else {
                $this->buildClassAndSubElement($className, $configuration);
            }
        }
    }

    private function buildClassAndSubElement($classname, $configuration) {
        $model = new Classes(["classname" => $classname, "configuration" => json_encode($configuration)]);
        $model->save();
        $result = Manager::getConnection("mysql")->getQueryBuilder(Classes::class)->select("*")->from(Classes::class)->where([["classname", "=", $classname]])->limit(1)->execute();
        if (count($result) === 1)
            $id = $result[0]->id;
        else
            return;
        if (isset($configuration["properties"])) {
            $propertiesTemp = $configuration["properties"];
            foreach ($propertiesTemp as $property => $value) {
                $submodel = new Property(["name" => $property, "configuration" => json_encode($value), "class" => $id]);
                $submodel->save();
            }
        }
        $methodTemp = $configuration;
        unset($methodTemp["properties"]);
        foreach ($methodTemp as $property => $value) {
            $submodel = new Method(["name" => $property, "configuration" => json_encode($value), "class" => $id]);
            $submodel->save();
        }
    }

    private function checkClassSubElement($tablename, $nameToFind, $classId, $configuration) {
        $objectInDB = Manager::getConnection("mysql")->getQueryBuilder($tablename)->select("*")->from($tablename)->where([["name", "=", $nameToFind], "AND" => ["class", "=", $classId]])->limit(1)->execute();
        if (count($objectInDB) === 1) {
            if ($objectInDB[0]->configuration != json_encode($configuration)) {
                $objectInDB[0]->configuration = json_encode($configuration);
                $objectInDB[0]->save();
            }
        }
    }
}