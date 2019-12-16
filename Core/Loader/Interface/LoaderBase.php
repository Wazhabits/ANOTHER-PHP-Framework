<?php

namespace Core\Loader;

interface LoaderBase
{
    /**
     * This function include all PHP files found in given directory (recursive)
     * @param $path
     * @param string $needle
     * @param string $constraint
     * @param int $depth
     * @return mixed
     */
    static function explore($path, $needle = "", $constraint = "", $depth = 0);
}