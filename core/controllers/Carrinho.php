<?php

namespace core\controllers;

use core\classes\Functions;
use core\models\Products;

class Carrinho
{
    private $produto;

    public function __construct()
    {
        $this->produto = new Products();
    }

    public function carrinho()
    {
        if (Functions::clienteLogado()) {
            Functions::redirect();
            return;
        }

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'carrinho',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    public function adicionar_carrinho()
    {
        $id_produto = trim(filter_var($_GET['id_produto'], FILTER_SANITIZE_NUMBER_INT));
        
        $carrinho = [];

        if (isset($_SESSION['carrinho'])) {
            $carrinho = $_SESSION['carrinho'];
        }
            
        if (key_exists($id_produto, $carrinho)) {
            $carrinho[$id_produto]++;
        } else {
            $carrinho[$id_produto] = 1;
        }
        
        $_SESSION['carrinho'] = $carrinho;
        
        $total_produtos = 0;

        foreach($carrinho as $produto) {
            $total_produtos += $produto;
        }

        echo $total_produtos;
    }

    public function limpar_carrinho()
    {
        $_SESSION['carrinho'] = [];
        //header('Refresh:0');
    }
}