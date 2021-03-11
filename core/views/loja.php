<div class="container-fluid espaco_fundo">
    <div class="row">
        <div class="col-12 text-center my-4">
            <a href="?r=loja&c=todos" class="btn btn-primary">Todos</a>
            <?php foreach($categorias as $key => $categoria): ?>    
                <a href="?r=loja&c=<?=$categoria['nome_categoria']?>" class="btn btn-primary">
                    <?=ucfirst($categoria['nome_categoria'])?>
                </a>
            <?php endforeach; ?>    
        </div>
    </div>

    <div class="row">
        <?php foreach($produtos as $produto): ?>
        <div class="col-sm-3 col-6 p-1">
            <div class="text-center p-3 card">
                <img src="assets/images/produtos/<?=$produto->imagem?>" alt="" class="img-fluid">
                <h4><?=$produto->nome_produto?></h4>
                <h3>R$ <?= number_format($produto->preco,2,",",".") ?></h3>
                <p><small><?=$produto->descricao?></small></p>
                <div>
                    <?php if ($produto->qtd_estoque <= 0): ?>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-shopping-cart me-2" aria-hidden="true"></i> Sem estoque
                        </button>                    
                    <?php else: ?>
                        <button class="btn btn-secondary btn-sm" onclick="adicionar_carrinho(<?=$produto->id?>)">
                            <i class="fas fa-shopping-cart me-2" aria-hidden="true"></i> Adicionar ao Carrinho
                        </button>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>