<?php


namespace Framework\Model;


use Core\Database\Model;

class Property extends Model
{
    /**
     * @var string $name
     * @type varchar
     * @nullable false
     * @size 100
     */
    public $name;

    /**
     * @var array $configuration
     * @type varchar
     * @size 1024
     */
    public $configuration;

    /**
     * @var
     * @foreign Framework\Model\Classes
     */
    public $class;
}