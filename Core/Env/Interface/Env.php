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
}