<?php


namespace Modules\Documentation\Model;

use Core\Database\Model;

class Classes extends Model
{
    /**
     * @var string $json
     * @type varchar
     * @size 4096
     * @nullable true
     */
    public $configuration = "";

    /**
     * @var string $class
     * @type varchar
     * @unique true
     * @size 255
     * @nullable false
     */
    public $classname = "";
}