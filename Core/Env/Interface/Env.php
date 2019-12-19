<?php


namespace Core\Env;


interface Env
{
    /**
     * This method return a configuration value or the configuration array
     * @param string $key
     * @return array|mixed|null
     */
    public function getConfiguration($key = "");

    /**
     * Return current execution time
     * @return int
     */
    public function getExecutionTime();

    /**
     * This function return the current milisecond
     * @return int
     */
    public function getMicrotime();

    /**
     * This function set a configuration pear key/value
     * @param $key
     * @param $value
     * @param $addToArray = false
     */
    public function set($key, $value, $addToArray = false);
}