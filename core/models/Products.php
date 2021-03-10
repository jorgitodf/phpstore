<?php

namespace core\models;

use core\classes\Database;
use core\classes\Functions;

class Products
{
    private $bd;
    private $table = 'products';

    public function __construct()
    {
        $this->bd = new Database();
    }


    public function lista_produtos_disponiveis($categoria)
    {
        $categorias = $this->lista_categorias();

        $sql = "SELECT * FROM {$this->table} WHERE visibilidade = 1 ";

        foreach($categorias as $key => $r) {
            if (in_array($categoria, $r)) {
                $sql .= "AND category_id = ".$r['id']."";
            }
        }

        return $this->bd->select($sql);
    }    


    public function lista_categorias()
    {
        $response = $this->bd->select("SELECT DISTINCT id, nome_categoria FROM category");

        $categorias = [];

        foreach($response as $key => $r) {
            $categorias[$key]['id'] = $r->id;
            $categorias[$key]['nome_categoria'] = strtolower($r->nome_categoria);
        }

        return $categorias;
    }    

}