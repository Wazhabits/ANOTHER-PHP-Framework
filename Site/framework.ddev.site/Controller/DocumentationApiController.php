<?php

namespace Framework\Controller;

use Core\Controller;
use Core\Database\Manager;
use Core\Response;
use Framework\Model\Classes;

class DocumentationApiController extends Controller
{
    /**
     * @site framework.ddev.site
     * @route /api/documentation
     */
    public function documentationApi() {
        $result = Manager::getConnection("mysql")->getQueryBuilder(Classes::class)
            ->select("*")
            ->from(Classes::class)
            ->execute();
        Response::setHeader(["Content-Type" => "application/json"]);
        Response::send();
        echo json_encode($result);
    }
}