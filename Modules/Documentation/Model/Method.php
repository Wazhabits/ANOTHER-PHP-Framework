<?php


namespace Modules\Documentation\Model;


use Core\Database\Model;

class Method extends Model
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
     * @type int
     * @size 11
     * @foreign Modules\Documentation\Model\Classes
     */
    public $class;
}