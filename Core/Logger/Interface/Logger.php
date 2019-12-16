<?php


namespace Core\Logger;


interface Logger
{
    /**
     * This function write a log file into the Logs folder
     * @param $key
     * @param $message
     * @param $status
     * @return bool
     */
    static function log($key, $message, $status);
}