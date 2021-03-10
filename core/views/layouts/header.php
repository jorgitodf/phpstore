<?php use core\classes\Functions; ?>

<div class="container-fluid navegacao fixed-top">
    <div class="row">
        <div class="col-6 p-3">
            <a href="?r=home"><?=APP_NAME?></a>
        </div>
        <div class="col-6 text-right p-3">
            <a href="?r=home" class="nav-item">Home</a>
            <a href="?r=loja" class="nav-item">Loja</a>

            <?php if (Functions::clienteLogado()): ?>
                <a href="" class="nav-item">Minha Conta</a>
                <a href="?r=logout" class="nav-item">Logout</a>
            <?php else: ?>
                <a href="?r=login" class="nav-item">Login</a>
                <a href="?r=novo_cliente" class="nav-item">Criar Conta</a>
            <?php endif; ?>

            <a href="?r=carrinho"><i class="fas fa-shopping-cart"></i></a>

            <span class="badge badge-secondary">10</span>
        </div>
    </div>
</div>