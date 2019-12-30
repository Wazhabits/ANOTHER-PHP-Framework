<?php


namespace Framework\Controller;

use Core\Controller;

class DefaultController extends Controller
{
    /**
     * @site i-tw.fr
     * @route /
     */
    public function index()
    {
        $this->render("index", []);
    }
}