<?php


namespace Framework\Controller;


use Core\Connection\Mysql;
use Core\Controller;
use Core\Database\Manager;
use Framework\Model\Classes;

class DocumentationController extends Controller
{
    /**
     * @site framework.ddev.site
     * @route /documentation
     */
    public function documentation() {

        $result = Manager::getConnection("mysql")->getQueryBuilder(Classes::class)
            ->select("*")
            ->from(Classes::class)
            ->execute();
        $this->render("documentation", ["result" => $result]);
    }
}