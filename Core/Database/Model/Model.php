<?php


namespace Core\Database;

use Core\Database\Model\Model as Base;

class Model implements Base
{
    public $sorting;
    public $createdate;
    public $updatedade;

    public function __construct($element, $sorting)
    {
        $this->createdate = $this->updatedade = time();
        $this->sorting = &$sorting;
        foreach ($element as $property => $value) {
            if (!is_numeric($property))
                $this->{$property} = &$value;
        }
    }

    public function hash(&$value) {
        $value = password_hash($value, PASSWORD_DEFAULT);
    }
}