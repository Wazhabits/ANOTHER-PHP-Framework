<?php


namespace Modules\Statistic\Event;


use Core\Database\Manager;
use Modules\Statistic\Model\Pages;
use Modules\Statistic\Model\Statistic;

class templatePreProcess
{
    /**
     * @event core/template.preProcess
     * @param array &$args
     */
    static function getStats(&$args) {
        $view = Manager::getConnection("mysql")->getQueryBuilder(Pages::class)->select(["view", "id"])->from(Pages::class)->where([["url", "=", $_SERVER["REQUEST_URI"]]])->execute();
        if (isset($view[0])) {
            $view[0]->view++;
            $args["__PAGE"]["view"] = $view[0]->view;
            $vote = Manager::getConnection("mysql")->getQueryBuilder(Statistic::class)->select(["result"])->from(Statistic::class)->where([["pageid", "=", $view[0]->id]])->execute();
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
            $args["__PAGE"]["like"] = $like;
            $args["__PAGE"]["dislike"] = $dislike;
            $view[0]->save();
        } else {
            $view = new Pages(["url" => $_SERVER["REQUEST_URI"], "view" => 0]);
            $view->save();
        }
    }
}