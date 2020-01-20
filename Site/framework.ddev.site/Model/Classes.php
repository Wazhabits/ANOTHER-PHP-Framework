<?php


namespace Framework\Model;

use Core\Database\Model;

class Classes extends Model
{
    /**
     * @var string $json
     * @type varchar
     * @size 4096
     * @nullable true
     */
    public $json = "";

    /**
     * @var string $class
     * @type varchar
     * @unique true
     * @size 255
     * @nullable false
     */
    public $class = "";
}