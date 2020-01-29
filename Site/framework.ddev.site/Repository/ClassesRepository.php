<?php


namespace Framework\Repository;


use Core\Database\Manager;
use Framework\Model\Classes;
use Framework\Model\Method;
use Framework\Model\Property;

class ClassesRepository
{
    /**
     * @return mixed
     */
    public function findAll() {
        $classes = Manager::getConnection("mysql")->getQueryBuilder(Classes::class)->select("*")->from(Classes::class)->execute();
        foreach ($classes as $id => &$class) {
            $class->methods = Manager::getConnection("mysql")->getQueryBuilder(Method::class)->select("*")->from(Method::class)->where([["class", "=", $class->id]])->execute();
            $class->properties = Manager::getConnection("mysql")->getQueryBuilder(Property::class)->select("*")->from(Property::class)->where([["class", "=", $class->id]])->execute();
        }
        return $classes;
    }
}