<?php


namespace Core;

use Core\Template\Parts\Conditions;
use Core\Template\Parts\Loop;
use Core\Template\Parts\Vars;
use Core\Template\Template as Base;

class Template implements Base
{
    private static $templatePath = "";
    private static $args = [];
    private static $baseTemplatePath = "";

    private static $varsBuilder;
    private static $conditionBuilder;
    private static $loopBuilder;

    /**
     * This function build a template
     * Cache include static format of a template so it don't include Loop / Condition / Vars
     * @param $buffer
     * @param $args
     */
    static function build(&$buffer, &$args) {
        /**
         * If we don't get cache or we are in Develop context
         */
        if (!self::getCache($buffer) || Kernel::$context === "Develop") {
            self::sectionalize($buffer);
            $buffer = str_replace("\n", "", $buffer);
            if (Kernel::$context === "Production") {
                self::putCache($buffer);
            }
        }
        self::$loopBuilder = new Loop($buffer, $args);
        self::$conditionBuilder = new Conditions($buffer, $args);
        /**
         * Show fully a var, only available in develop context
         */
        if (Kernel::$context === "Develop") {
            self::debug($buffer);
        }
        self::$varsBuilder = new Vars($buffer, $args);
        Response::send();
        Environment::set("time", "ControllerCall:" . Environment::getExecutionTime(). "ms", true);
    }

    /**
     * @param $buffer
     * @return bool
     */
    private static function getCache(&$buffer) {
        if (file_exists(self::calcTemplatePath())) {
            $buffer = file_get_contents(self::calcTemplatePath());
            return true;
        }
        return false;
    }

    /**
     * @param $buffer
     */
    private static function putCache(&$buffer) {
        if (file_exists(self::calcTemplatePath())) {
            unlink(self::calcTemplatePath());
        }
        file_put_contents(self::calcTemplatePath(), $buffer);
    }

    /**
     * @return string
     */
    private static function calcTemplatePath() {
        return "Cache" . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, "", strtoupper(md5(str_replace(self::$baseTemplatePath, "", self::$templatePath)))) . ".cache";
    }

    /**
     * This function render all template need from 1 master template
     * @param string $templatePath
     * @param array $args
     */
    static function render($templatePath = "index", &$args = [])
    {
        self::init($templatePath, $args);
        ob_start(Template::class . "::boot");
        echo Files::read(self::$templatePath);
        ob_end_flush();
        /**
         * Exec event postProcess
         */
        Event::exec("core/template.postProcess");
    }

    private static function init(&$templatePath, &$args) {
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
        self::$args = self::object_to_array($args);
        self::$baseTemplatePath = PATH_SITE;
        self::$templatePath = self::$baseTemplatePath . $_SERVER["HTTP_HOST"] . DIRECTORY_SEPARATOR . "Resource" . DIRECTORY_SEPARATOR
            . $templatePath . Environment::getConfiguration("TEMPLATE_EXT");
    }

    /**
     * This function initialize the template engine
     * @param $buffer
     * @return mixed
     */
    static function boot($buffer)
    {
        /**
         * Exec event preRender
         */
        Event::exec("core/template.preBuild", $buffer);
        /**
         *
         */
        self::build($buffer, self::$args);
        /**
         * Exec event postRender
         */
        Event::exec("core/template.postBuild", $buffer);
        return $buffer;
    }
    /**
     * This function replace section call to the associated section recursively
     * @param $buffer
     */
    static function sectionalize(&$buffer)
    {
        $matches = [];
        preg_match_all("/{section:([\w|\w\/\w+]*)}/", $buffer, $matches);
        foreach ($matches[1] as $sectionPath) {
            $sectionContent = Files::read(self::$baseTemplatePath . $sectionPath
                . Environment::getConfiguration("TEMPLATE_EXT"));
            self::sectionalize($sectionContent);
            $buffer = str_replace("{section:" . $sectionPath . "}", $sectionContent, $buffer);
        }
    }

    /**
     * This function add the debug to frontend, this flag show a vars in html (each kind of var)
     */
    static function debug(&$buffer) {
        $matches = [];
        preg_match_all("/{debug:(.*?)}/s", $buffer, $matches);
        $i = 0;
        while ($i < count($matches[1])) {
            self::$args["execution_time"] = Environment::getConfiguration("TIME");
            switch ($matches[1][$i]) {
                case "__args":
                    $buffer = str_replace("{debug:" . $matches[1][$i] . "}", self::showArray(self::object_to_array(self::$args)), $buffer);
                    break;
                default:
                    $buffer = str_replace("{debug:" . $matches[1][$i] . "}", self::showArray(self::object_to_array(self::$args[$matches[1][$i]])), $buffer);
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
    static function showArray($array) {
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