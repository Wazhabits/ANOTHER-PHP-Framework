<?php


namespace Core;

use Core\Template\Template as Base;

class Template implements Base
{
    private static $templatePath = "";
    private static $args = [];
    private static $baseTemplatePath = "";

    /**
     * This function render all template need from 1 master template
     * @param string $templatePath
     * @param array $args
     */
    static function render($templatePath = "index", &$args = [])
    {
        /**
         * Prepare args for event
         */
        $argumentForEvent = [
            &$templatePath,
            &$args
        ];
        /**
         * Exec event preProcess
         */
        Event::exec("core/template.preProcess", $argumentForEvent);
        self::$args = $args;
        foreach ($args as $name => $arg) {
            if (is_object($arg))
                $args[$name] = self::object_to_array($arg);
        }
        self::$baseTemplatePath = PATH_SITE . DIRECTORY_SEPARATOR
            . $_SERVER["HTTP_HOST"] . DIRECTORY_SEPARATOR . "Resource" . DIRECTORY_SEPARATOR;
        self::$templatePath = self::$baseTemplatePath
            . $templatePath . Kernel::getEnvironment()->getConfiguration("TEMPLATE_EXT");
        ob_start(Template::class . "::build");
        echo Files::read(self::$templatePath);
        ob_end_flush();
        /**
         * Exec event postProcess
         */
        Event::exec("core/template.postProcess");
    }

    /**
     * This function build a template
     * @param $buffer
     * @return mixed
     */
    static function build($buffer)
    {
        /**
         * Exec event preRender
         */
        Event::exec("core/template.preRender", $buffer);
        /**
         * Include all section needed in templates
         */
        self::sectionalize($buffer);
        /**
         * Template foreach in templates
         */
        self::makeLoop($buffer);
        /**
         * Put vars at the place of markers in templates
         */
        self::setVars($buffer);
        /**
         * Show fully a var, only available in develop context
         */
        if (Kernel::getEnvironment()->getConfiguration("APPLICATION_CONTEXT") === "Develop")
            self::debug($buffer);
        /**
         * Exec event postRender
         */
        Event::exec("core/template.postRender", $buffer);
        return $buffer;
    }

    /**
     * This function make a foreach in templates.
     * Entry :
     * ...
     * {foreach:vars>key=var}
     * <h1>My super {key}</h1>
     * ....
     * {end}
     * @param &$buffer
     */
    static function makeLoop(&$buffer) {
        $matches = [];
        preg_match_all("/({foreach:(.*?)>(.*?)=(.*?)}(.*?){end})/s", $buffer, $matches);
        /**
         * Matches vars
         */
        $contentsToReplace = &$matches[1];
        $variableNames = &$matches[2];
        $keyNames = &$matches[3];
        $valueNames = &$matches[4];
        $contents = &$matches[5];
        $i = 0;
        while ($i < count($variableNames)) {
            /**
             * Match elements
             */
            // Name of the key of foreach
            $keyName = &$keyNames[$i];
            // Name of value
            $valueName = &$valueNames[$i];
            // Argument array gave by controller
            $argument = &self::$args[$variableNames[$i]];
            // Keys of argument array
            $argumentElementKeys = array_keys($argument);
            // Content to replace into loop
            $content = &$contents[$i];
            $contentToReplace = $contentsToReplace[$i];
            if (isset($argument) && is_array($argument)) {
                // Content to replace on this loop
                $subContent = $content;
                // Index of loop
                $loopIndex = 0;
                while ($loopIndex < count($argumentElementKeys)) {
                    // BaseArray-ValueNameInForeach-KeyInBaseArray
                    $nameOfValueInMemoryVar = $variableNames[$i] . $valueName . $argumentElementKeys[$loopIndex];
                    // Create memory arg in the base arg array with the good value
                    self::$args[$nameOfValueInMemoryVar] =  &$argument[$argumentElementKeys[$loopIndex]];
                    $subContent = str_replace("{" . $valueName . "}", "{" . $nameOfValueInMemoryVar . "}", $subContent);

                    // BaseArray-ValueNameInForeach-KeyInBaseArray
                    $nameOfKeyInMemoryVar = $variableNames[$i] . $keyName . $argumentElementKeys[$loopIndex];
                    // Create memory arg in the base arg array with the good value
                    self::$args[$nameOfKeyInMemoryVar] =  &$argumentElementKeys[$loopIndex];
                    $subContent = str_replace("{" . $keyName . "}", "{" . $nameOfKeyInMemoryVar . "}", $subContent);
                    $loopIndex++;
                }
                $buffer = str_replace($contentToReplace, $subContent, $buffer);
            }
            $i++;
        }
    }

    /**
     * This function replace section call to the associated section recursively
     * @param $buffer
     */
    static function sectionalize(&$buffer)
    {
        $matches = [];
        preg_match_all("/\{section\:(.*)\}/", $buffer, $matches);
        foreach ($matches[1] as $sectionPath) {
            $sectionContent = Files::read(self::$baseTemplatePath . $sectionPath
                . Kernel::getEnvironment()->getConfiguration("TEMPLATE_EXT"));
            self::sectionalize($sectionContent);
            $buffer = str_replace("{section:" . $sectionPath . "}", $sectionContent, $buffer);
        }
    }

    /**
     * This function replace vars call in template by his value
     * @param $buffer
     */
    static function setVars(&$buffer)
    {
        $matches = [];
        preg_match_all("/{([\w|\w.\w+]*)}/", $buffer, $matches);
        foreach ($matches[1] as $vars) {
            // On match les tableaux
            if (count($varsIsArrayElement = explode(".", $vars)) === 2) {
                $argumentName = $varsIsArrayElement[0];
                $argument = self::$args[$argumentName][$varsIsArrayElement[1]];
                $buffer = str_replace("{" . $vars . "}", $argument, $buffer);
            } else {
                $argument = self::$args[$vars];
                if (is_array($argument))
                    $buffer = str_replace("{" . $vars . "}", implode(", ", $argument), $buffer);
                if (is_string($argument) || is_int($argument))
                    $buffer = str_replace("{" . $vars . "}", $argument, $buffer);
            }
        }
    }

    /**
     * This function add the {debug:Vars} to frontend, this flag show a vars in html (each kind of var)
     * use :
     *  - {debug:varname}
     * special :
     *  - {debug:__all} this marker show all vars accessible in template
     * @param $buffer
     */
    static function debug(&$buffer) {
        $matches = [];
        preg_match_all("/{debug:(.*?)}/s", $buffer, $matches);
        $i = 0;
        while ($i < count($matches[1])) {
            switch ($matches[1][$i]) {
                case "__args":
                    $buffer = str_replace("{debug:" . $matches[1][$i] . "}", self::showArray(self::$args), $buffer);
                    break;
                default:
                    $buffer = str_replace("{debug:" . $matches[1][$i] . "}", self::showArray(self::$args[$matches[1][$i]]), $buffer);
                    break;
            }
            $i++;
        }
    }

    /**
     * This function show recursively an array as ul>li in html string
     * @param $array
     * @return string
     */
    private static function showArray($array) {
        $result = "<ul class='array'>";
        $keys = array_keys($array);
        $i = 0;
        while ($i < count($array)) {
            if (is_array($array[$keys[$i]]))
                $result .= "<li><span class='key'>" . $keys[$i] . "</span>: " . self::showArray($array[$keys[$i]]) . "</li>";
            else
                $result .= "<li><span class='key'>" . $keys[$i] . "</span>: '<span class='value'>" . $array[$keys[$i]] . "</span>'</li>";
            $i++;
        }
        return $result . "</ul>";
    }

    /**
     * This function convert an object to an array recursively
     * @param $obj
     * @return array
     */
    static function object_to_array($obj) {
        $array = (array) $obj;
        foreach ($array as &$attribute) {
            if (is_object($attribute) || is_array($attribute)) $attribute = self::object_to_array($attribute);
        }
        return $array;
    }
}