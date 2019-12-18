<?php


namespace Core;

use Core\Annotation\Annotation as Base;

class Annotation implements Base
{
    /**
     * @var array
     */
    public $classesDocumentation = [];
    /**
     * This array contain all documentation of code
     * @var array
     */
    public $documentation = [];

    public function __construct()
    {
        $this->getClasses();
    }

    /**
     * This function return classes annotation documentation filter by method or not
     * @param $classname
     * @param string $method
     * @return bool|mixed
     */
    public function getDocumentation($classname = "", $method = "")
    {
        if ($classname === "")
            return $this->documentation;
        if ($method !== "")
            return (array_key_exists($method, $this->documentation[$classname])) ? $this->documentation[$classname][$method] : false;
        else
            return $this->documentation[$classname];
    }

    /**
     * @param $marker
     * @return array
     */
    public function getByMarker($marker)
    {
        $result = [];
        foreach ($this->documentation as $classes => $configuration) {
            foreach ($configuration as $method => $comment) {
                if ($comment) {
                    if (array_key_exists($marker, $comment)) {
                        foreach ($comment[$marker] as $markerValue) {
                            $result[$classes][$method] = trim($markerValue);
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * This function parse all loaded classes and read these documentation
     */
    private function getClasses()
    {
        $loadedClasses = get_declared_classes();
        foreach ($loadedClasses as $class) {
            if (strpos($class, "\\") !== false) {
                try {
                    try {
                        $reflectedClass = new \ReflectionClass($class);
                        $namespace = explode("\\", $class)[0];
                        $this->document($class, $reflectedClass);
                        $this->documentation["classes"][$namespace][] = str_replace($namespace . "\\", "", $class);
                    } catch (\Exception $exception) {
                        var_dump($exception);
                    }
                } catch (\Exception $exception) {
                    var_dump($exception);
                }
            }
        }
    }

    /**
     * This function parse all method of class to extract documentation
     * @param $classname
     * @param \ReflectionClass $reflectedClass
     */
    private function document($classname, \ReflectionClass $reflectedClass)
    {
        $methods = $reflectedClass->getMethods();
        foreach ($methods as $method) {
            $this->readDocComment($this->clearComment($method->getDocComment()), $this->documentation[$classname][$method->name]);
        }
    }

    /**
     * This function clear documentation string and return it as array
     * @param $comments
     * @return array
     */
    private function clearComment($comments)
    {
        $comments = explode(" * ", trim($comments));
        $i = 0;
        while ($i < count($comments)) {
            $comments[$i] = trim(str_replace("/**", "", str_replace("*/", "", $comments[$i])));
            if ($comments[$i] === "" || $comments[$i] === " ")
                unset($comments[$i]);
            $i++;
        }
        return array_values($comments);
    }

    /**
     * This function build the documentation array of a specific method
     * @param $comments
     * @param $classDocumentation
     */
    private function readDocComment($comments, &$classDocumentation)
    {
        foreach ($comments as $comment) {
            if (strpos($comment, "@") !== false) {
                $matches = [];
                preg_match_all("/\@(\w*)\s(.*)/", $comment, $matches);
                if (count($matches[1])) {
                    $varsName = $matches[1][0];
                    $varsComment = $matches[2][0];
                    $classDocumentation[$varsName][] = $varsComment;
                }
            } else {
                $classDocumentation["description"] = $comment;
            }
        }
    }
}