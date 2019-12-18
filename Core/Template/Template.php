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
         * Put vars at the place of markers in templates
         */
        self::setVars($buffer);
        /**
         * Exec event postRender
         */
        Event::exec("core/template.postRender", $buffer);
        return $buffer;
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
        extract(self::$args);
        preg_match_all("/\{(.*)\}/", $buffer, $matches);
        foreach ($matches[1] as $vars) {
            $matchesArray = [];
            preg_match_all("/(.*)\[(.*)\]/", $vars, $matchesArray);
            if (count($matchesArray[1]) > 0) {
                $buffer = str_replace("{" . $matchesArray[1][0] . "[" . $matchesArray[2][0] . "]}", ${$matchesArray[1][0]}[$matchesArray[2][0]], $buffer);
            } else {
                if (is_array(${$vars}))
                    $buffer = str_replace("{" . $vars . "}", implode(", ", ${$vars}), $buffer);
                if (is_string(${$vars}))
                    $buffer = str_replace("{" . $vars . "}", ${$vars}, $buffer);
            }
        }
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