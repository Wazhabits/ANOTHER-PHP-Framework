<?php


namespace Modules\Statistic\Model;

use Core\Database\Model;

class Statistic extends Model
{
    /**
     * @var $result
     * @type integer
     * @size 11
     */
    public $result;
    /**
     * @var $token
     * @type varchar
     * @size 255
     */
    public $token;
    /**
     * @var $pages
     * @foreign Modules\Statistic\Model\Pages
     * @type integer
     * @size 11
     */
    public $pageid;
}