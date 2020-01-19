<?php


namespace Core\Database;

use Core\Database\Model\Model as Base;

class Model implements Base
{
    public function __construct($element, $sorting)
    {
        foreach ($element as $property => $value) {
            if (!is_numeric($property))
                $this->{$property} = $value;
        }
    }
}