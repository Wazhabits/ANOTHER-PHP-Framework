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
class Select implements Type
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

    public function innerJoin(array $innerJoinConfiguration) {
        $this->configuration["join"]["inner"] = $innerJoinConfiguration;
        $this->configuration["join"]["inner"]["sql"] = "";
        $i = 0;
        foreach ($innerJoinConfiguration as $join) {
            /**
             * "join" => [
             *      "inner" => [
             *          [
             *              ["table1" => "field"],
             *              ["table2" => "field"],
             *               "operator" => "="
             *          ],
             *       ],
             * ]
             */
            $table1 = array_keys($join[0])[0];
            $table2 = array_keys($join[1])[0];
            $this->configuration["join"]["inner"]["sql"] .= " INNER JOIN `" . $table2 . "` ON `" . $table1 . "`.`" . $join[0][$table1] . "` " . $join["operator"] . " `" . $table2 . "`.`" . $join[1][$table2] . "`";
            $i++;
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
        $sql = " WHERE ";
        foreach ($whereConfiguration as $condition) {
            if ($i !== 0) {
                if (isset($condition["concatenator"]))
                    $sql .= " " . $condition["concatenator"] . " `" . $condition[0] . "` " . $condition[1] . " \"" . $this->quote($condition[2]) . "\"";
                else
                    $sql .= " AND `" . $condition[0] . "` " . $condition[1] . " \"" . $this->quote($condition[2]) . "\"";
            }
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
        $query = "";
        if (isset($this->configuration["fields"]["sql"]))
            $query .= $this->configuration["fields"]["sql"];
        if (isset($this->configuration["from"]["sql"]))
            $query .= $this->configuration["from"]["sql"];
        if (isset($this->configuration["join"]["inner"]["sql"]))
            $query .= $this->configuration["join"]["inner"]["sql"];
        if (isset($this->configuration["where"]["sql"]))
            $query .= $this->configuration["where"]["sql"];
        return $query;
    }

    /**
     * @param $string
     * @return mixed
     */
    private function quote($string) {
        $string = str_replace('"', '\"', $string);
        $string = str_replace('\\', '/', $string);
        return $string;
    }
}