<?php


namespace Core\Database;

use Core\Connection\Mysql;
use Core\Database\Model\Model as Base;

class Model implements Base
{
    /**
     * @var int $id
     */
    public $id = null;

    /**
     * Sorting
     * @var int $sorting
     */
    public $sorting;

    /**
     * Date of creation
     * @var int $createdate
     */
    public $createdat;

    /**
     * Time of update
     * @var $updatedade
     */
    public $updatedat;

    /**
     * Model constructor.
     * @param $element
     * @param $sorting
     */
    public function __construct($element, $sorting)
    {
        $this->createdat = time();
        $this->sorting = &$sorting;
        foreach ($element as $property => $value) {
            if (!is_numeric($property))
                $this->{$property} = $value;
        }
    }

    /**
     * Hash a password
     * @param &$value
     */
    public function hash(&$value) {
        $value = password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * @return array
     */
    public function get() {
        return get_object_vars($this);
    }

    /**
     * @return $this
     */
    public function save() {
        $this->updatedat = time();
        if ($this->id === null) {
            unset($this->id);
            echo "<pre><code>", var_dump(
            Mysql::getQueryBuilder(get_class($this))->insert(get_class($this))->values($this->get())->getConfiguration()), "</code></pre>";
            Mysql::getQueryBuilder(get_class($this))->insert(get_class($this))->values($this->get())->execute();
        } else {
            $id = $this->id;
            unset($this->createdat, $this->id);
            echo "<pre><code>", var_dump(
            Mysql::getQueryBuilder(get_class($this))->update(get_class($this))->fields($this->get())->where([["id", "=", $id]])->getQuery()), "</code></pre>";
            Mysql::getQueryBuilder(get_class($this))->update(get_class($this))->fields($this->get())->where([["id", "=", $id]])->execute();
        }
        return $this;
    }
}