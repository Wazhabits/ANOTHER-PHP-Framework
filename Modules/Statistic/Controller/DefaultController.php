<?php


namespace Modules\Statistic\Controller;


use Core\Controller;
use Core\Database\Manager;
use Modules\Statistic\Model\Pages;
use Modules\Statistic\Model\Statistic;

class DefaultController extends Controller
{
    /**
     * get current page stats
     * @param $path
     * @return mixed
     */
    private function getPage($path) {
        return Manager::getConnection("mysql")
            ->getQueryBuilder(Pages::class)
            ->select("*")
            ->from(Pages::class)
            ->where([["url", "=", str_replace("|", "/", urldecode($path))]])
            ->execute();
    }

    /**
     * get current session stat
     * @param $path
     * @return mixed
     */
    private function getStat($page) {
        return Manager::getConnection("mysql")
            ->getQueryBuilder(Statistic::class)
            ->select("*")
            ->from(Statistic::class)
            ->where([["token", "=", session_id()]], ["pageid", "=", $page["id"]])
            ->execute();
    }

    /**
     * Function counting view
     * @param $args
     */
    public function view($args) {
        $path = $args["route"]["arguments"]["path"];
        $page = $this->getPage($path);
        if ($page === false || empty($page)) {
            $page = new Pages([
                "url" => str_replace("|", "/", urldecode($path)),
                "view" => 0
            ]);
        } else {
            $page = $page[0];
            $page->view = $page->view + 1;
        }
        $page->save();
    }

    /**
     * Function who made a vote
     */
    public function voteNo($args) {
        $path = $args["route"]["arguments"]["path"];
        $page = $this->getPage($path);
        if ($page === false || empty($page)) {
            $page = new Pages([
                "url" => str_replace("|", "/", urldecode($path)),
                "view" => 0
            ]);
            $page->save();
        } else {
            $statistic = $this->getStat($page);
            if ($statistic === false || empty($statistic)) {
                $statistic = new Statistic([
                    "pageid" => $page[0]->id,
                    "token" => session_id(),
                    "result" => '-1'
                ]);
            } else {
                $statistic = $statistic[0];
                $statistic->result = -1;
            }
            $statistic->save();
        }
    }

    /**
     * Function who made a vote
     */
    public function voteYes($args) {
        $path = $args["route"]["arguments"]["path"];
        $page = $this->getPage($path);
        if ($page === false || empty($page)) {
            $pages = new Pages([
                "url" => str_replace("|", "/", urldecode($path)),
                "view" => 0
            ]);
            $pages->save();
        } else  {
            $statistic = $this->getStat($page);
            if ($statistic === false || empty($statistic)) {
                $statistic = new Statistic([
                    "pageid" => $page[0]->id,
                    "token" => session_id(),
                    "result" => '1'
                ]);
            } else {
                $statistic = $statistic[0];
                $statistic->result = 1;
            }
            $statistic->save();
        }
    }
}