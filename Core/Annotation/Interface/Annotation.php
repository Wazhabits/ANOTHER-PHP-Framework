<?php


namespace Core\Annotation;


interface Annotation
{
    /**
     * This function return classes annotation documentation filter by method or not
     * @param $classname
     * @param string $method
     */
    public function getDocumentation($classname, $method = "");
}