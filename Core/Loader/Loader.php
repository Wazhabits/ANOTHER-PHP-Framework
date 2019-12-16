<?php

namespace Core;

include_once __DIR__ . "/Interface/LoaderBase.php";

use \Core\Loader\LoaderBase;

class Loader implements LoaderBase
{
    static function explore($path, $needle = "", $constraint = "")
    {
        $scan = glob($path . DIRECTORY_SEPARATOR . "*");
        foreach ($scan as $path) {
            if (is_dir($path)) {
                self::explore($path, $needle, $constraint);
            } else {
                if (strpos($path, ".php") !== false) {
                    if (($needle === "" || strpos($path, $needle) !== false) && ($constraint === "" || strpos($path, $constraint) === false)) {
                        require_once $path;
                    }
                }
            }
        }
    }
}