<?php

namespace core\controllers;

use core\classes\Functions;
use core\models\Products;

class Loja
{
    private $produto;

    public function __construct()
    {
        $this->produto = new Products();
    }

    public function loja()
    {
        $cat = 'todos';

        if (isset($_GET['c'])) {
            $cat = trim(filter_var($_GET['c'], FILTER_SANITIZE_STRING));
        }

        $produtos = $this->produto->lista_produtos_disponiveis($cat);
        $categorias = $this->produto->lista_categorias();
        
        $dados = [
            'produtos' => $produtos,
            'categorias' => $categorias
        ];

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'loja',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
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
}