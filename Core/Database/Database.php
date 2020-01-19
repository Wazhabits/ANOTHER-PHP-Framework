<?php


namespace Core;

use Core\Database\Database as base;

/**
 * Class Database
 * @package Core
 */
class Database implements base
{
    private $type = "";
    private $identity = [];
    private $connection = null;

    /**
     * Database constructor. this will build a connection with a database
     * @param string $type
     * @param array $identity
     */
    public function __construct($type = "", $identity = []) {
        if ($type === "")
            $this->type = Kernel::getEnvironment()->getConfiguration("DATABASE_TYPE");
        else
            $this->type = $type;
        if (empty($identity)) {
            switch ($this->type) {
                case "mysql":
                    $this->identity = [
                        "host" => Kernel::getEnvironment()->getConfiguration("MYSQL_HOST"),
                        "port" => Kernel::getEnvironment()->getConfiguration("MYSQL_PORT"),
                        "name" => Kernel::getEnvironment()->getConfiguration("MYSQL_NAME"),
                        "user" => Kernel::getEnvironment()->getConfiguration("MYSQL_USER"),
                        "pass" => Kernel::getEnvironment()->getConfiguration("MYSQL_PASS")
                    ];
                    break;
                default:
                    break;
            }
        } else {
            $this->identity = $identity;
        }
        $this->connectionBuilder();
    }

    /**
     * This function will make an connection's instance
     */
    private function connectionBuilder() {
        if (class_exists("Core\\Connection\\" . ucfirst(strtolower($this->type)))) {
            $connectorName = "Core\\Connection\\" . ucfirst(strtolower($this->type));
            $connectorName::define($this->identity);
            $this->identity = null;
        } else {
            Logger::log("database", "Try to get connection on non-existing database type '" . ucfirst($this->type) . "'", Logger::$ERROR_LEVEL);
            $this->connection = false;
            // TODO: Create an exception thrower
        }
    }
}