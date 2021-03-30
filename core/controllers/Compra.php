<?php

namespace core\controllers;

use core\classes\Functions;
use core\models\Purchasing;

class Compra
{
    private $purchasing;

    public function __construct()
    {
        $this->purchasing = new Purchasing();
        if (!Functions::clienteLogado()) {
            Functions::redirect();
            return;
        }
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            Functions::redirect();
            return;
        }

        $purchasing = $this->purchasing->getPurchasingByIdUser($_SESSION['id_cliente']);

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'historico-compras',
            'layouts/footer',
            'layouts/html_footer',
        ], compact("purchasing"));
    }
}