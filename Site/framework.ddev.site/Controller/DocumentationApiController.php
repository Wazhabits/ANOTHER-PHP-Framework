<?php

namespace Framework\Controller;

use Core\Controller;
use Core\Database\Manager;
use Core\Response;
use Framework\Model\Classes;
use Framework\Repository\ClassesRepository;

class DocumentationApiController extends Controller
{
    /**
     * @site framework.ddev.site
     * @route /api/documentation
     */
    public function documentationApi() {
        $repository = new ClassesRepository();
        $result = $repository->findAll();
        Response::setHeader(["Content-Type" => "application/json"]);
        Response::send();
        echo json_encode($result);
    }
}