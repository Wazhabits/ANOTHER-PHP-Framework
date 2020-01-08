<?php


namespace Core\Connection\Mysql\QueryType;


/**
 * Class Select
 * @package Core\Connection\Mysql
 * Configuration array : [
 *      "fields": [
 *
 *      ],
 *      "where": [
 *          "AND" => ["fieldname", "operator", "value"],
 *          "AND" => ["fieldname", "operator", "value"],
 *          "OR" => ["fieldname", "operator", "value"]
 *      ]
 * ]
 */
class Select
{
    private $configuration;

    /**
     * Select constructor.
     * @param array $fields
     */
    public function __construct($fields) {
        if ($fields !== "*") {
            $this->configuration["fields"] = $fields;
            foreach ($fields as $field) {
                $this->configuration["fields"][] = "`" . $field . "`";
            }
            $this->configuration["fields"]["sql"] = "SELECT " . implode(",", $this->configuration["fields"]);
        } else {
            $this->configuration["fields"][] = "*";
            $this->configuration["fields"]["sql"] = "SELECT * ";
        }
    }

    /**
     * @param string|array $tablename
     * @return $this
     */
    public function from($tablename) {
        if (is_array($tablename)) {
            $this->configuration["from"] = $tablename;
            $this->configuration["from"]["sql"] = " FROM " . implode(",", $this->configuration["from"]);
        } else {
            $this->configuration["from"][] = $tablename;
            $this->configuration["from"]["sql"] = " FROM " . $tablename;
        }
        return $this;
    }

    /**
     * @param array $whereConfiguration
     * @return $this
     */
    public function where(array $whereConfiguration) {
        $i = 0;
        $this->configuration["where"] = $whereConfiguration;
        $sql = "WHERE ";
        foreach ($whereConfiguration as $concat => $condition) {
            if ($i !== 0)
                $sql .= " " . $concat . " `" . $condition[0] . "` " . $condition[1] . " \"" . $this->quote($condition[2]) . "\"";
            else
                $sql .= "`" . $condition[0] . "` " . $condition[1] . " \"" . $this->quote($condition[2]) . "\"";
            $i++;
        }
        $this->configuration["where"]["sql"] = $sql;
        return $this;
    }

    /**
     * @return string $sql
     */
    public function getQuery() {
        return $this->configuration["fields"]["sql"] . $this->configuration["from"]["sql"] . $this->configuration["where"]["sql"];
    }

    private function quote($string) {
        $string = str_replace('"', '\"', $string);
        $string = str_replace('\\', '/', $string);
        return $string;
    }
}