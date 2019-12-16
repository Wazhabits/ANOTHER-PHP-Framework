<?php

namespace Core;

include_once __DIR__ . "/Interface/LoaderBase.php";

use \Core\Loader\LoaderBase;

class Loader implements LoaderBase
{
    static function explore($path, $needle = "", $constraint = "", $depth = 0)
    {
        $scan = glob($path . DIRECTORY_SEPARATOR . "*");
        foreach ($scan as $path) {
            if (is_dir($path)) {
                self::explore($path, $needle, $constraint, $depth + 1);
            } else {
                if (strpos($path, ".php") !== false) {
                    if (($needle === "" || strpos($path, $needle) !== false) && ($constraint === "" || strpos($path, $constraint) === false) && $depth !== 0) {
                        require_once $path;
                    }
                }
            }
        }
    }
}