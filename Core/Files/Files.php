<?php


namespace Core;

use Core\Files\Files as Base;

class Files implements Base
{
    /**
     * This function return file contents
     * @param $path
     * @return false|string
     */
    static function read($path) {
        if (file_exists($path))
            return file_get_contents($path);
        else
            return false;
    }

    /**
     * This function put content into $path given
     * @param $path
     * @param $content
     * @return bool|int|mixed
     */
    static function put($path, $content) {
        if (!file_exists($path))
            self::test($path);
        return file_put_contents($path, $content . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    /**
     * This function delete $path file
     * @param $path
     * @return mixed|void
     */
    static function delete($path) {
        if (file_exists($path))
            unlink($path);
    }

    /**
     * This function create a $path file given
     * @param $path
     * @param $content = ""
     * @return mixed|void
     */
    static function create($path, $content = "") {
        self::test($path);
        $fd = fopen($path, "a+");
        fclose($fd);
        if ($content !== "")
            self::put($path, $content);
    }

    /**
     * This function test a $path given and create folder if not exist
     * @param $path
     * @return bool|mixed
     */
    static function test($path) {
        if (!file_exists($path)) {
            $elements = explode(DIRECTORY_SEPARATOR, $path);
            $accumulator = "";
            foreach ($elements as $element) {
                if ($accumulator === "")
                    $accumulator = $element;
                else
                    $accumulator .= $element;
                if (strpos($element, ".") === false && !file_exists($accumulator) && $accumulator !== "")
                    mkdir($accumulator);
                if (strpos($element, "."))
                    return file_exists($accumulator);
                $accumulator .= DIRECTORY_SEPARATOR;
            }
        }
        return true;
    }
}