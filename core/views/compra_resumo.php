<div class="container espaco_fundo_carrinho">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="my-2">Resumo da Sua compra</h4>
            <hr>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12">

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

                <h4 class="bg-dark text-white p-2">Dados do Cliente</h4>
                <div class="row">
                    <div class="col">
                        <p>Nome: <strong><?=$cliente->name?></strong></p>
                        <p>E-mail: <strong><?=$cliente->email?></strong></p>
                    </div>
                    <div class="col"></div>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" onchange="usar_outro_endereco()" name="alterar_endereco_entrega" id="alterar_endereco_entrega" class="form-check-input">
                    <label class="form-check-label" for="alterar_endereco_entrega">Entregar em outro Endereço</label>
                </div>

                <div id="outro_endereco" style="display: none;">
                    Outro endereço
                </div>

                <div class="row my-3">
                    <div class="col">
                        <button onclick="" class="btn btn-primary">Cancelar</button>
                        <span class="ml-4" id="confirmar_limpar_carrinho" style="display: none;">Tem certeza?
                            <button class="btn btn-danger" onclick="">Não</button>
                            <a href="?r=limpar_carrinho" class="btn btn-success">Sim</a>    
                        </span>
                    </div>
                    <div class="col text-right">
                        <a href="" class="btn btn-primary">Escolher Forma de Pagamento</a>
                    </div>
                </div>
            </div>   

        </div>
    </div>
</div>