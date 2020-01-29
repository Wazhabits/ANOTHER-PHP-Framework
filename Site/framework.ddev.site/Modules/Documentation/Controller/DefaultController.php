<?php

namespace Modules\Documentation\Controller;

use Core\Controller;
use Core\Database\Manager;
use Core\Logger;
use Core\Response;
use Framework\Model\Classes;
use Framework\Repository\ClassesRepository;
use Modules\Documentation\Maker;

class DefaultController extends Controller
{
    /**
     * Get all documentation (json)
     */
    public function index() {
        $repository = new ClassesRepository();
        $result = $repository->findAll();
        Response::setHeader(["Content-Type" => "application/json"]);
        Response::send();
        echo json_encode($result);
    }

    /**
     * Documentation maker
     */
    public function make() {
        Logger::log("documentation", "Builder running", Logger::$DEFAULT_LEVEL);
        $maker = new Maker();
        $maker->extract();
        Response::setHeader(["location" => "/api/documentation"]);
    }
}