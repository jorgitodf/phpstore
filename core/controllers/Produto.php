<?php

namespace core\controllers;

use core\classes\Functions;
use core\models\Products;
use Exception;

class Produto
{
    private $produto;

    public function __construct()
    {
        $this->produto = new Products();
    }

    //===========================================================================================//
    public function buscar_produtos_disponiveis()
    {
        $cat = 'todos';

        if (Functions::clienteLogado()) {
            Functions::redirect();
            return;
        }

        $produtos = $this->produto->lista_produtos_disponiveis($cat);

        //Functions::printDados($produtos);

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'loja',
            'layouts/footer',
            'layouts/html_footer',
        ], ['produtos' => $produtos]);
    }

}