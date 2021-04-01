<?php

namespace core\classes;

use Exception;
use PDO;
use PDOException;

class Carrinho
{
    /** Monta a lista de produtos que estão no carrinho para preparar para o envio do e-mail da confirmação da compra */
    public static function mountListPurschasing($results)
    {
        $string_produtos = [];

        foreach ($results as $value) {
            $quantidade = $_SESSION['carrinho'][$value->id];
            $string_produtos[] = "{$quantidade}x {$value->nome_produto} 
            - R$ " . number_format($value->preco, 2, ",", ".");
        }
        
        $dados_compra['lista_compra'] = $string_produtos;
        $dados_compra['total'] = "R$ " . number_format($_SESSION['total_compra'], 2, ",", ".");
        $dados_compra['dados_pagamento'] = [
            'numero_conta' => '12345679',
            'codigo_compra' => $_SESSION['codigo_compra'],
            'total' => "R$ " . number_format($_SESSION['total_compra'], 2, ",", ".")
        ];

        return $dados_compra;
    }
}