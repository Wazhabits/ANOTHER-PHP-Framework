<?php


namespace Framework\Controller;

use Core\Annotation;
use Core\Controller;
use Core\Database\Connection\Mysql\Type\BaseType;
use Core\Kernel;

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
     * @route /connection
     */
    public function connection() {
        var_dump(Kernel::getAnnotation()->getDocumentation(BaseType::class, "innerJoin")); die;
        $selectQueryAdvanced = Kernel::get("mysql")->getConnection()->getQueryBuilder()->select("*")
            ->from("user")
            ->innerJoin([
                [
                    ["user" => "groupid"],
                    ["group" => "id"],
                    "operator" => "!="
                ]
            ])
            ->where([
                ["name", "=", "babtou"],
            ])
            ->limit(1)
            ->getQuery();
        $deleteQuery = Kernel::get("mysql")
            ->getConnection()
            ->getQueryBuilder()
            ->delete("user")
            ->where([
                ["name", "=", "babtou"],
            ])
            ->getQuery();
        $selectQuery =
            Kernel::get("mysql")
                ->getConnection()
                ->getQueryBuilder()
                ->select("*")
                ->from("user")
                ->getQuery();
        $updateQuery = Kernel::get("mysql")->getConnection()->getQueryBuilder()->update("user")
            ->fields([
                "name" => "Lapinou",
                "pseudo" => 'Qui fait "loulou"'
            ])
            ->where([
                ["name", "=", "babtou"],
            ])
            ->getQuery();
        $insertQuery = Kernel::get("mysql")->getConnection()->getQueryBuilder()->insert("user")
            ->values([
                "id" => "",
                "name" => "Lapinou",
                "pseudo" => 'Qui fait "loulou"',
                "pass" => 'Marab--ouutou@#{""\\',
            ])
            ->getQuery();
        $resultUpdate = Kernel::get("mysql")->getConnection()->exec($updateQuery);
        $this->render("connection", [
            "connection" => Kernel::get("mysql")->getConnection(),
            "queryBuilder" => Kernel::get("mysql")->getConnection()->getQueryBuilder(),
            "select" => $selectQueryAdvanced,
            "update" => $updateQuery,
            "delete" => $deleteQuery,
            "insert" => $insertQuery,
            "resultUpdate" => $resultUpdate,
            "result" => Kernel::get("mysql")->getConnection()->exec($selectQuery)->fetchAll()
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