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

    public function detalheCompra()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'GET' && !isset($_GET['id']) && !is_numeric($_GET['id']) && !isset($_SESSION['csrf_token'])) {
            Functions::redirect();
            return;
        }

        $id_compra = trim(filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT));

        $purchasing = $this->purchasing->getPurchasingByIdWithStatus($id_compra);

        Functions::Layout([
            'layouts/html_header',
            'layouts/header',
            'detalhe-compra',
            'layouts/footer',
            'layouts/html_footer',
        ], compact("purchasing"));
    }
}