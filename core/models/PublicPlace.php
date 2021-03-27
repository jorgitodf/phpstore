<?php

declare(strict_types=1);

namespace core\models;

use core\classes\Database;

class PublicPlace
{
    private $bd;
    private $table = 'public_place';

    public function __construct()
    {
        $this->bd = new Database();
    }

    public function getAllPublicPlace(): array
    {
        return $this->bd->select("SELECT * FROM {$this->table}");
    }

    public function getAllPublicPlaceById()
    {
        $array = [];

        $res = $this->bd->select("SELECT id FROM {$this->table}");

        foreach ($res as $value) {
            $array[] = $value->id;
        }

        return $array;
    }
}
