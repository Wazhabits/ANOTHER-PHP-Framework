<?php

namespace Core;

use Core\Logger\Logger as LoggerBase;

class Logger implements LoggerBase
{
    static $ERROR_LEVEL = 2;
    static $WARNING_LEVEL = 1;
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
        if ($key === "" || $message === "" || $status > (int)Kernel::getEnvironment()->getConfiguration("LOG_LEVEL"))
            return false;
        Files::put(self::makeLogPath($key, $status), Kernel::getEnvironment()->getMicrotime() . "|" . $message);
        return true;
    }

    /**
     * This function build the log file path
     * @param $key
     * @param $status
     * @return string
     */
    private static function makeLogPath($key, $status) {
        $directory =  PATH_LOG . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR
            . self::$FOLDERS[$status] . DIRECTORY_SEPARATOR;
        $timeHash = date(Kernel::getEnvironment()->getConfiguration("LOG_FORMAT"), time());
        $fileExt = ".log";
        return $directory . $timeHash . $fileExt;
    }
}