<?php


namespace Modules\Statistic\Event;


use Core\Database\Manager;
use Modules\Statistic\Model\Comment;
use Modules\Statistic\Model\Pages;
use Modules\Statistic\Model\Statistic;

class templatePreProcess
{
    /**
     * get stats of current page
     * @param $path
     * @return mixed
     */
    static function getPage($path) {
        return Manager::getConnection("mysql")
            ->getQueryBuilder(Pages::class)
            ->select("*")
            ->from(Pages::class)
            ->where([["url", "=", str_replace("|", "/", urldecode($path))]])
            ->execute();
    }

    /**
     * Get vote of current page
     * @param $id
     * @return mixed
     */
    static function getVote($id) {
        $vote = Manager::getConnection("mysql")->getQueryBuilder(Statistic::class)->select("*")->from(Statistic::class)->where([["pageid", "=", $id]])->execute();
        $like = 0;
        $dislike = 0;
        if (is_array($vote)) {
            foreach ($vote as $v) {
                if ($v->result == 1) {
                    $like++;
                } else {
                    $dislike++;
                }
            }
        }
        $temp["like"] = ($like === 0) ? "0 %" : (100 * ($like + $dislike)) / $like . "%";
        $temp["dislike"] = ($dislike === 0) ? "0 %" : (100 * ($like + $dislike)) / $dislike . " %";
        return $temp;
    }

    /**
     * Get comment of current page
     * @param $id
     * @return mixed
     */
    static function getComment($id) {
        return Manager::getConnection("mysql")->getQueryBuilder(Comment::class)->select("*")->from(Comment::class)->where([["pageid", "=", $id]])->execute();
    }

    /**
     * get current stats for template
     * @event core/template.preProcess
     * @param array &$args
     */
    static function getStats(&$args) {
        $view = self::getPage($_SERVER["REQUEST_URI"]);
        if (isset($view[0])) {
            $view[0]->view++;
            $args["__PAGE"]["vote"] = self::getVote($view[0]->id);
            $args["__PAGE"]["view"] = $view[0]->view;
            $args["__COMMENT"] = self::getComment($view[0]->id);
            $view[0]->save();
        } else {
            $view = new Pages(["url" => $_SERVER["REQUEST_URI"], "view" => 0]);
            $view->save();
        }
    }
}