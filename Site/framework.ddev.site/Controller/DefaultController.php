<?php


namespace Framework\Controller;

use Core\Annotation;
use Core\Connection\Mysql;
use Core\Controller;
use Core\Database\Connection\Mysql\Type\BaseType;
use Core\Database\Manager;
use Core\Kernel;
use Core\Response;
use Framework\Model\User;

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
     * @route /api
     */
    public function testResponseWithoutTemplate() {
        Response::setHeader("Content-Type: application/json");
        var_dump(json_encode(["babtou" => ["batbi" => "bou"]]));
    }

    /**
     * @site framework.ddev.site
     * @route /connection
     */
    public function connection() {
        $selectQueryAdvanced = Manager::getConnection("mysql")->getQueryBuilder(User::class)->select("*")
            ->from(User::class)
            ->innerJoin([
                [
                    [User::class => "groupid"],
                    ["group" => "id"],
                    "operator" => "!="
                ]
            ])
            ->where([
                ["name", "=", "babtou"],
            ])
            ->limit(1)
            ->getQuery();
        $deleteQuery = Manager::getConnection("mysql")->getQueryBuilder(User::class)
            ->delete(User::class)
            ->where([
                ["name", "=", "babtou"],
            ])
            ->getQuery();
        $selectQuery = Manager::getConnection("mysql")->getQueryBuilder(User::class)
                ->select("*")
                ->from(User::class)
                ->getQuery();
        $updateQuery = Manager::getConnection("mysql")->getQueryBuilder(User::class)->update(User::class)
            ->fields([
                "name" => "Lapinou",
                "pseudo" => 'Qui fait "loulou"'
            ])
            ->where([
                ["name", "=", "babtou"],
            ])
            ->getQuery();
        $insertQuery = Manager::getConnection("mysql")->getQueryBuilder(User::class)->insert(User::class)
            ->values([
                "id" => "",
                "name" => "Lapinou",
                "pseudo" => 'Qui fait "loulou"',
                "pass" => 'Marab--ouutou@#{""\\',
            ])
            ->getQuery();
        $resultUpdate = Manager::getConnection("mysql")->getQueryBuilder(User::class)->update(User::class)
            ->fields([
                "name" => "Lapinou",
                "pseudo" => 'Qui fait "loulou"'
            ])
            ->where([
                ["name", "=", "babtou"],
            ]);
        $result = Manager::getConnection("mysql")->getQueryBuilder(User::class)
            ->select("*")
            ->from(User::class)
            ->execute();
        $this->render("connection", [
            "queryBuilder" => Manager::getConnection("mysql")->getQueryBuilder(User::class),
            "select" => $selectQueryAdvanced,
            "update" => $updateQuery,
            "delete" => $deleteQuery,
            "insert" => $insertQuery,
            "resultUpdate" => $resultUpdate,
            "result" => $result
        ]);
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