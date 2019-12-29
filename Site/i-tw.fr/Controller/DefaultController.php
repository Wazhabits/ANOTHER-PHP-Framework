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
        $json = file_get_contents("http://91.162.251.47:80/");
        $videos = json_decode($json, true);
        $this->render("index", ["videos" => $videos]);
    }
}