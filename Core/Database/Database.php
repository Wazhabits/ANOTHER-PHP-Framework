<?php


namespace Core;

use Core\Database\Database as base;

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
                        "pass" => Kernel::getEnvironment()->getConfiguration("MYSQL_PASS"),
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
     * @return null
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * This function will make an connection's instance
     */
    private function connectionBuilder() {
        switch ($this->type) {
            case "mysql":
                $connectorName = "Core\\Connection\\" . ucfirst(strtolower($this->type));
                $this->connection = new $connectorName($this->identity);
                break;
            default:
                break;
        }
        $this->identity = null;
    }
}