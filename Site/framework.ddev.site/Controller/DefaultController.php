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
        $this->render("index", ["coucou" => "Bonjour", "liste" => [["Ma"], "Liste", "A", "Virgule"], "message" => ["coucou", "babtou"]]);
    }

    /**
     * @site framework.ddev.site
     * @route /loop
     */
    public function loop() {
        $array = [
           "Hello" => "World",
           "I" => "am Happy",
           "To" => "show",
           "you" => "that"
        ];
        $this->render("loop", ["array" => $array]);
    }

    /**
     * @site framework.ddev.site
     * @route /section
     */
    public function section() {
        $this->render("section", []);
    }

    /**
     * @site framework.ddev.site
     * @route /condition
     */
    public function condition() {
        $this->render("condition", ["coucou" => "Bonjour"]);
    }

    /**
     * @site framework.ddev.site
     * @route /vars
     */
    public function variables() {
        $this->render("vars", [
            "message" => "bonjour",
            "submessage" => [
                "ceci",
                "est",
                "une",
                "suite" => "suite de message",
                "suite2" => ["de manière", "recusrive", "et sous forme de tableau"]
            ]
        ]);
    }
}