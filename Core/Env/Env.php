<?php

namespace Core;

use Core\Env\Env as Base;

class Env implements Base
{
    private $configuration = [];

    public function __construct($path)
    {
        if (file_exists($path))
            $this->read($path);
        else
            return false;
        define("EXECUTION_BEGIN", $this->getMicrotime());
        return $this;
    }

    /**
     * This function read a .env file and set configuration
     * @param $path
     */
    private function read($path)
    {
        $content = Files::read($path);
        $vars = explode("\n", $content);
        /**
         * Foreach setting in .env
         * Array : [Key, Value]
         */
        foreach ($vars as $var) {
            if (substr($var, 0, 1) !== "#" && trim($var) !== "") {
                $configuration = explode("=", $var);
                $this->set($configuration[0], $configuration[1]);
            }
        }
    }

    /**
     * This function set a configuration pear key/value
     * @param $key
     * @param $value
     */
    private function set($key, $value) {
        $this->configuration[strtoupper($key)] = trim($value);
    }

    /**
     * This method return a configuration value or the configuration array
     * @param string $key
     * @return array|mixed|null
     */
    public function getConfiguration($key = "") {
        if ($key === "")
            return $this->configuration;
        else {
            if (array_key_exists($key, $this->configuration))
                return $this->configuration[$key];
            else
                return null;
        }
    }

    /**
     * This function return the current milisecond
     * @return int
     */
    public function getMicrotime() {
        return (int)(microtime(true) * 1000);
    }

    /**
     * Return current execution time
     * @return int
     */
    public function getExecutionTime() {
        return $this->getMicrotime() - EXECUTION_BEGIN;
    }
}