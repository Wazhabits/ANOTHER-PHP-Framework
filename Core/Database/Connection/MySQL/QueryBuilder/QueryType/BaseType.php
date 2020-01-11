<?php


namespace Core\Database\Connection\Mysql\Type;

/**
 * Class BaseType
 * @package Core\Database\Connection\Mysql\Type
 */
abstract  class BaseType implements Type
{
    protected static $configuration;

    /**
     * @param integer $nbr
     * @return $this
     */
    public function limit($nbr) {
        self::$configuration["limit"]["number"] = $nbr;
        self::$configuration["limit"]["sql"] = " LIMIT " . $nbr;
        return $this;
    }

    /**
     * @param integer $nbr
     * @return $this
     */
    public function offset($nbr) {
        self::$configuration["offset"]["number"] = $nbr;
        self::$configuration["offset"]["sql"] = " OFFSET " . $nbr;
        return $this;
    }

    /**
     * @param array $innerJoinConfiguration
     * @return $this;
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
    public function innerJoin(array $innerJoinConfiguration) {
        self::$configuration["join"]["inner"] = $innerJoinConfiguration;
        self::$configuration["join"]["inner"]["sql"] = "";
        $i = 0;
        foreach ($innerJoinConfiguration as $join) {
            $table1 = array_keys($join[0])[0];
            $table2 = array_keys($join[1])[0];
            self::$configuration["join"]["inner"]["sql"] .= " INNER JOIN `" . strtolower($table2)
                . "` ON `" . strtolower($table1) . "`.`" . strtolower($join[0][$table1]) . "` "
                . $join["operator"]
                . " `" . strtolower($table2) . "`.`" . strtolower($join[1][$table2]) . "`";
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
        self::$configuration["where"] = $whereConfiguration;
        $sql = " WHERE ";
        foreach ($whereConfiguration as $condition) {
            if ($i !== 0) {
                if (isset($condition["concatenator"]))
                    $sql .= " " . $condition["concatenator"] . " `" . strtolower($condition[0]) . "` " . $condition[1] . " \"" . $this->quote($condition[2]) . "\"";
                else
                    $sql .= " AND `" . strtolower($condition[0]) . "` " . $condition[1] . " " . $this->quote($condition[2]);
            }
            else
                $sql .= "`" . strtolower($condition[0]) . "` " . $condition[1] . " " . $this->quote($condition[2]);
            $i++;
        }
        self::$configuration["where"]["sql"] = $sql;
        return $this;
    }

    /**
     * @param $string
     * @return mixed
     */
    protected function quote($string) {
        $string = str_replace('"', '\"', $string);
        $string = str_replace('\\', '/', $string);
        return '"' . $string . '"';
    }
}