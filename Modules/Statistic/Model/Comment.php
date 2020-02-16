<?php


namespace Modules\Statistic\Model;

use Core\Database\Model;

class Comment extends Model
{
    /**
     * @var $result
     * @type varchar
     * @size 1024
     */
    public $comment;
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