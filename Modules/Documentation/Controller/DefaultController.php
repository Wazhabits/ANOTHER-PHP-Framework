<?php

namespace Modules\Documentation\Controller;

use Core\Controller;
use Core\Logger;
use Core\Response;
use Modules\Documentation\Maker;
use Modules\Documentation\Repository\ClassesRepository;

class DefaultController extends Controller
{
    /**
     * @var ClassesRepository $repository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new ClassesRepository();
    }

    /**
     * Get all documentation (json)
     */
    public function index() {
        return $this->repository->findAll();
    }

    /**
     * Documentation maker
     */
    public function make() {
        Logger::log("documentation", "Builder running", Logger::$DEFAULT_LEVEL);
        $maker = new Maker();
        $maker->extract();
        Response::setHeader(["location" => "/api/documentation/get/all"]);
    }

    /**
     * Retrieve a class documentation
     * @param $args
     * @return array
     */
    public function get($args) {
        $value = $this->repository->findBy("classname", urldecode($args["route"]["arguments"]["classname"]));
        if (empty($value))
            Response::setStatus(404);
        return $value;
    }
}