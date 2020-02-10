<?php


namespace Modules\Documentation\Repository;


use Core\Database\Manager;
use Modules\Documentation\Model\Classes;
use Modules\Documentation\Model\Method;
use Modules\Documentation\Model\Property;

class ClassesRepository
{
    /**
     * @return mixed
     */
    public function findAll() {
        $classes = Manager::getConnection("mysql")->getQueryBuilder(Classes::class)->select("*")->from(Classes::class)->execute();
        foreach ($classes as $id => &$class) {
            $class->methods = $this->getMethod($id);
            $class->properties = $this->getProperties($id);
        }
        return $classes;
    }

    /**
     * Get all method of specific class
     * @param $id
     * @return mixed
     */
    private function getMethod($id) {
        return Manager::getConnection("mysql")->getQueryBuilder(Method::class)->select("*")->from(Method::class)->where([["class", "=", $id]])->execute();
    }

    /**
     * Get all properties of specific class
     * @param $id
     * @return mixed
     */
    private function getProperties($id) {
        return Manager::getConnection("mysql")->getQueryBuilder(Property::class)->select("*")->from(Property::class)->where([["class", "=", $id]])->execute();
    }

























    public function findBy($column, $value) {
        $classes = Manager::getConnection("mysql")
            ->getQueryBuilder(Classes::class)
            ->select("*")
            ->from(Classes::class)
            ->where([[$column, "=", $value]])
            ->limit(1)
            ->execute();


        $classes[0]->methods = $this->getMethod($classes[0]->id);
        $classes[0]->properties = $this->getProperties($classes[0]->id);


        return $classes;
    }
}