<?php

namespace Modules\Documentation\Controller;

use Core\Controller;
use Core\Database\Manager;
use Core\Response;
use Framework\Model\Classes;
use Framework\Repository\ClassesRepository;

class DefaultController extends Controller
{
    /**
     */
    public function index() {
        $repository = new ClassesRepository();
        $result = $repository->findAll();
        Response::setHeader(["Content-Type" => "application/json"]);
        Response::send();
        echo json_encode($result);
    }
}