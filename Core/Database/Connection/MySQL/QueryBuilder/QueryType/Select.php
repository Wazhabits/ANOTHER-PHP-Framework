<?php


namespace Core\Database\Connection\Mysql\Type;

/**
 * Class Select
 * @package Core\Connection\Mysql
 * Configuration array : [
 *      "fields": [
 *
 *      ],
 *      "where": [
 *          ["fieldname", "operator", "value"],
 *          ["fieldname", "operator", "value", "concatenator": "AND"],
 *          ["fieldname", "operator", "value", "concatenator": "OR"]
 *      ]
 * ]
 */
class Select extends BaseType
{
    /**
     * Select constructor.
     * @param array $fields
     */
    public function __construct($fields) {
        parent::$configuration = [];
        if ($fields !== "*") {
            parent::$configuration["fields"] = $fields;
            foreach ($fields as $field) {
                parent::$configuration["fields"][] = "`" . $field . "`";
            }
            parent::$configuration["fields"]["sql"] = "SELECT " . implode(",", parent::$configuration["fields"]);
        } else {
            parent::$configuration["fields"][] = "*";
            parent::$configuration["fields"]["sql"] = "SELECT * ";
        }
    }

    /**
     * @param string|array $tablename
     * @return $this
     */
    public function from($tablename) {
        if (is_array($tablename)) {
            parent::$configuration["from"] = $tablename;
            parent::$configuration["from"]["sql"] = " FROM " . strtolower(implode(",", parent::$configuration["from"]));
        } else {
            parent::$configuration["from"][] = $tablename;
            parent::$configuration["from"]["sql"] = " FROM " . strtolower($tablename);
        }
        return $this;
    }

    /**
     * @return string $sql
     */
    public function getQuery() {
        $query = "";
        if (!isset(parent::$configuration["fields"]["sql"]) || !isset(parent::$configuration["from"]["sql"]))
            return false;
        $query .= parent::$configuration["fields"]["sql"] . parent::$configuration["from"]["sql"];
        if (isset(parent::$configuration["join"]["inner"]["sql"]))
            $query .= parent::$configuration["join"]["inner"]["sql"];
        if (isset(parent::$configuration["where"]["sql"]))
            $query .= parent::$configuration["where"]["sql"];
        if (isset(parent::$configuration["offset"]["sql"]))
            $query .= parent::$configuration["offset"]["sql"];
        if (isset(parent::$configuration["limit"]["sql"]))
            $query .= parent::$configuration["limit"]["sql"];
        return $query;
    }
}