<?php

namespace Core;

include_once __DIR__ . "/Interface/LoaderBase.php";

use \Core\Loader\LoaderBase;

class Loader implements LoaderBase
{
    static $CLASSES = [];

    /**
     * This function load classes by path with constraint or needle
     * @param $path
     * @param string $needle
     * @param string $constraint
     * @param int $depth
     * @return mixed|void
     */
    static function explore($path, $needle = "", $constraint = "", $depth = 0)
    {
        if (file_exists($path . ".routing")) {
            Files::$ROUTING[] = $path . ".routing";
        }
        $scan = glob($path . DIRECTORY_SEPARATOR . "*");
        foreach ($scan as $path) {
            if (is_dir($path)) {
                self::explore($path, $needle, $constraint, $depth + 1);
            } else {
                if (!in_array($path, self::$CLASSES)) {
                    if (strpos($path, ".php") !== false) {
                        if (($needle === "" || strpos($path, $needle) !== false) && ($constraint === "" || strpos($path, $constraint) === false)) {
                            self::$CLASSES[] = $path;
                            require_once $path;
                        }
                    }
                }
            }
        }
    }
}