<?php

namespace Core;

use Core\Logger\Logger as LoggerBase;

class Logger implements LoggerBase
{
    /**
     * Need by Logger to define in which folder put the file
     * @var int $ERROR_LEVEL
     */
    static $ERROR_LEVEL = 2;
    /**
     * Need by Logger to define in which folder put the file
     * @var int $WARNING_LEVEL
     */
    static $WARNING_LEVEL = 1;
    /**
     * Need by Logger to define in which folder put the file
     * @var int $DEFAULT_LEVEL
     */
    static $DEFAULT_LEVEL = 0;
    static $FOLDERS = ["", "warning", "error"];

    /**
     * This function write in log file split by specific format
     * @param string $key is use as sub-folder of Log folder
     * @param string $message log content
     * @param int $status this value is needed for split log like warning/errors
     * @return bool
     */
    public static function log($key = "", $message = "", $status = 0)
    {
        if ($key === "" || $message === "" || $status > (int)Environment::getConfiguration("LOG_LEVEL"))
            return false;
        Files::put(self::makeLogPath($key, $status), Environment::getMicrotime() . "|" . $message);
        return true;
    }

    /**
     * This function build the log file path
     * @param $key
     * @param $status
     * @return string
     */
    private static function makeLogPath($key, $status) {
        if (Environment::getConfiguration("LOG_SPLIT_BY_SITE") === "true")
            $directory =  PATH_LOG  . str_replace(".", "-", $_SERVER["HTTP_HOST"]) . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR
                . self::$FOLDERS[$status] . DIRECTORY_SEPARATOR;
        else
            $directory =  PATH_LOG . $key . DIRECTORY_SEPARATOR
                . self::$FOLDERS[$status] . DIRECTORY_SEPARATOR;
        Files::test($directory);
        $timeHash = date(Environment::getConfiguration("LOG_FORMAT"), time());
        $fileExt = ".log";

        return $directory . $timeHash . $fileExt;
    }
}