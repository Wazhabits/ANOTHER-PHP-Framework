<?php


namespace Core\Database;


interface Connection
{
    public function exec($query);
    public function fetchAll();
    public function getQueryBuilder();
}