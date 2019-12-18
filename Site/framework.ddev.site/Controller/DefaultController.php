<?php


namespace Framework\Controller;

use Core\Controller;

class DefaultController extends Controller
{
    /**
     * @site framework.ddev.site
     * @route /
     */
    public function index()
    {
        $this->render("index", ["coucou" => "Bonjour", "liste" => ["Ma", "Liste", "A", "Virgule"], "message" => ["coucou", "babtou"]]);
    }
}