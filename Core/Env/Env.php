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
        foreach ($vars as $var) {
            $configuration = explode("=", $var);
            $this->set($configuration[0], $configuration[1]);
        }
    }

    /**
     * This function set a configuration pear key/value
     * @param $key
     * @param $value
     */
    private function set($key, $value) {
        $this->configuration[$key] = $value;
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
}