<?php


namespace Core\Database\Connection\MySQL\Type;


interface Type
{
    /**
     * @return string
     */
    public function getQuery();
}