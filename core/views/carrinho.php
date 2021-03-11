<h3>Carrinho</h3>
<!-- <button onclick="limpar_carrinho()" class="btn btn-primary">Limpar Carrinho</button> -->
<a href="?r=limpar_carrinho" class="btn btn-primary">Limpar Carrinho</a>

<div class="container-fluid espaco_fundo">
    <div class="row">
        <div class="col-12">
            <?php if ($carrinho == null): ?>
                <p>Carrinho Vazio</p>
                <p><a href="?r=loja" class="btn btn-primary">Loja</a></p>
            <?php else: ?>
                <p>Carrinho...</p>
            <?php endif;?>

        </div>
    </div>
</div>