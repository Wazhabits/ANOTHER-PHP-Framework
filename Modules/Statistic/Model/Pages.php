<?php


namespace Modules\Statistic\Model;

use Core\Database\Model;

class Pages extends Model
{
    /**
     * @var $url
     * @type varchar
     * @size 1024
     */
    public $url;
    /**
     * @var $view
     * @type integer
     * @size 11
     * @default 0
     */
    public $view;
}