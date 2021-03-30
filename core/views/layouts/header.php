<?php

use core\classes\Functions;

$total_produtos = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $value) {
        $total_produtos += $value;
    }
}
?>

<header class="container-fluid navegacao fixed-top d-inline-flex col -col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <nav class="navbar navbar-light col-6">
        <span class="navbar-brand mb-0 h1"><a href="?r=home"><?=APP_NAME?></a></span>
    </nav>
    <div class="col-6 text-right p-3">
        <a href="?r=home" class="nav-item">Home</a>
        <a href="?r=loja" class="nav-item">Loja</a>

        <?php if (Functions::clienteLogado()) : ?>
            <a href="?r=minha-conta" class="nav-item">Minha Conta</a>
            <a href="?r=logout" class="nav-item">Logout</a>
        <?php else : ?>
            <a href="?r=login" class="nav-item">Login</a>
            <a href="?r=novo_cliente" class="nav-item">Criar Conta</a>
        <?php endif; ?>

        <a href="?r=carrinho"><i class="fas fa-shopping-cart"></i></a>

        <span class="badge badge-warning" id="carrinho"><?=$total_produtos == 0 ? '' : $total_produtos?></span>
    </div>
</header>