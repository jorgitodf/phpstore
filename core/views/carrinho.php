<div class="container espaco_fundo_carrinho">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="my-2">Sua compra</h4>
            <!-- <button onclick="limpar_carrinho()" class="btn btn-primary">Limpar Carrinho</button> -->
            <hr>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if ($carrinho == null): ?>
                <p class="text-center">Não existem produtos no carrinho.</p>
                <div class="mt-4 text-center">
                    <a href="?r=loja" class="btn btn-primary">Ir para a Loja</a>
                </div>
                
            <?php else: ?>
            <div style="margin-bottom: 50px;">
                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Produto</th>
                            <th class="text-center">Quantidade</th>
                            <th class="text-right">Valor Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 0; $total_rows = count($carrinho); ?>
                        <?php foreach($carrinho as $produto): ?>
                            <?php if ($index < $total_rows - 1): ?> 
                                <tr>
                                    <td><img src="assets/images/produtos/<?=$produto['imagem']?>" alt="" class="img-fluid" width="50px"></td>
                                    <td class="align-middle"><?=$produto['titulo']?></td>
                                    <td class="align-middle text-center"><?=$produto['quantidade']?></td>
                                    <td class="text-right align-middle"><h5>R$ <?= number_format($produto['preco'],2,",",".")?></h5></td>
                                    <td class="text-start align-middle">
                                        <a href="?r=remover_produto_carrinho&id_produto=<?=$produto['id_produto']?>" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></a>
                                    </td>
                                </tr>
                            <?php else: ?>  
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><h5>Total:</h5></td>
                                    <td class="text-right"><h4>R$ <?= number_format($produto,2,",",".")?></h4></td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>       
                            <?php $index++ ?>       
                        <?php endforeach;?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col">
                        <!-- <a href="?r=limpar_carrinho" class="btn btn-primary">Limpar Carrinho</a>  -->
                        <button onclick="confirmar_limpar_carrinho()" class="btn btn-primary">Limpar Carrinho</button>
                        <span class="ml-4" id="confirmar_limpar_carrinho" style="display: none;">Tem certeza?
                            <button class="btn btn-danger" onclick="limpar_carrinho_off()">Não</button>
                            <a href="?r=limpar_carrinho" class="btn btn-success">Sim</a>    
                        </span>
                    </div>
                    <div class="col text-right">
                        <a href="?r=loja" class="btn btn-primary">Comprar Mais</a>
                        <a href="?r=finalizar_compra" class="btn btn-primary">Finalizar Compra</a>
                    </div>
                </div>
            </div>   
            <?php endif;?>
        </div>
    </div>
</div>