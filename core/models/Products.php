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

        foreach ($categorias as $key => $r) {
            if (in_array($categoria, $r)) {
                $sql .= "AND category_id = " . $r['id'] . "";
            }
        }

        return $this->bd->select($sql);
    }


    public function lista_categorias()
    {
        $response = $this->bd->select("SELECT DISTINCT id, nome_categoria FROM category");

        $categorias = [];

        foreach ($response as $key => $r) {
            $categorias[$key]['id'] = $r->id;
            $categorias[$key]['nome_categoria'] = strtolower($r->nome_categoria);
        }

        return $categorias;
    }

    public function verificar_produto_estoque($id_produto)
    {
        $parametros = [
            ':id' => $id_produto
        ];

        $res = $this->bd->select("SELECT * FROM {$this->table} 
            WHERE id = :id AND visibilidade = 1 AND qtd_estoque > 0", $parametros);

        return count($res) != 0 ? true : false;
    }

    public function buscar_produtos_por_id($ids)
    {
        return $this->bd->select("SELECT * FROM {$this->table} WHERE id IN ($ids)");
    }

    public function updateProductsAfterPurchase(array $dados)
    {
        foreach ($dados as $key => $value) {
            $res = $this->bd->update(
                "UPDATE products SET qtd_estoque = :quantidade WHERE id = :id",
                [':quantidade' => $value['quantidade'], ":id" => $value['id']]
            );
        }

        if (is_bool($res)) {
            return true;
        } else {
            return $res;
        }
    }
}