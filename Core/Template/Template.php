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
        self::setLoop($buffer);
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
     * Entrée :
     * ...
     * {foreach:vars>key=var}
     * @param $buffer
     */

    static function setLoop(&$buffer) {
        $matches = [];
        preg_match_all("/(({foreach:(.*?)\sas\s(.*?)\s\=\>\s(.*?)})(.*?){end})/s", $buffer, $matches);
        foreach ($matches[2] as $index => $expression)  {
            $variableName = $matches[3][$index];
            $keyName = $matches[4][$index];
            $valueName = $matches[5][$index];
            $content = $matches[6][$index];
            $subMatches = [];
            $tmpContent = "";
            preg_match_all("/\{(.*)\}/", $content, $subMatches);
            // Pour chaque element du foreach
            foreach (self::$args[$variableName] as $key => $element) {
                // Pour chaque variable a l'interieur du foreach
                foreach ($subMatches[1] as $subMatch) {
                    $tmpContent .= $content;
                    $tmpContent = str_replace("{" . $keyName . "}",  $key, $tmpContent);
                    $tmpContent = str_replace("{" . $valueName . "}",  $element, $tmpContent);
                    // Si tableau
                    if (strpos($subMatch, ".") !== false) {
                        if (explode(".", $subMatch)[0] === $valueName) {
                            if (is_array($element) && isset($element[explode(".", $subMatch)[1]]))
                                $tmpContent .= str_replace("{" . $subMatch . "}", $element[explode(".", $subMatch)[1]], $tmpContent);
                        }
                    }
                }
            }
            $buffer = str_replace($matches[1][$index], $tmpContent, $buffer);
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
        preg_match_all("/\{(.*)\}/", $buffer, $matches);
        foreach ($matches[1] as $vars) {
            // On match les tableaux
            if (count($varsIsArrayElement = explode(".", $vars)) > 1) {
                $i = 1;
                $indices = "";
                while ($i < count($varsIsArrayElement)) {
                        $indices .= ((is_numeric($varsIsArrayElement[$i])) ?
                                $varsIsArrayElement[$i]
                                : "'" . $varsIsArrayElement[$i] . "'");
                    $i++;
                }
                $argumentName = $varsIsArrayElement[0];
                $argument = self::$args[$argumentName];
                $value = $argument{$indices};
                $buffer = str_replace("{" . $vars . "}", $value, $buffer);
            } else {
                $argument = self::$args[$vars];
                if (is_array($argument))
                    $buffer = str_replace("{" . $vars . "}", implode(", ", $argument), $buffer);
                if (is_string($argument))
                    $buffer = str_replace("{" . $vars . "}", $argument, $buffer);
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