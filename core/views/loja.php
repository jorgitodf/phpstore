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
                <h3>R$ <?=$produto->preco?></h3>
                <p><small><?=$produto->descricao?></small></p>
                <div>
                    <button>Adicionar ao Carrinho</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>