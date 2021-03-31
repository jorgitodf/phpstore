<div class="container-fluid col-sm-12 col-lg-12 col-md-12 container-historico-compras">
    <div class="row my-4 rhc">
        <div class="col-sm-10 col-lg-10 col-md-10 offset-sm-1 offset-lg-1 offset-md-1" id="div-his-comp">
            <h3>Pedidos</h3>
            <?php if (count($purchasing) > 0) : ?>
            <?php foreach ($purchasing as $key => $value) : ?>
            <div class="card">
                <div class="card-header d-flex flex-row justify-content-around">
                    <div>
                        <div><span>Data do Pedido</span></div>
                        <div><span><b><?= formataDataPtBr($purchasing[$key]->data_compra) ?></b></span></div>
                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                    </div>
                    <div>
                        <div><span>Total</span></div>
                        <div><span><b><?= formatarMoedaPtBr($purchasing[$key]->total) ?></b></span></div>
                    </div>
                    <div>
                        <div><span>Número da Compra: <b><?= $purchasing[$key]->codigo_compra ?></b></span></div>
                        <div class="text-center my-1"><span
                                class="badge <?= $purchasing[$key]->cor ?>"><?= $purchasing[$key]->status ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-body my-1 d-flex justify-content-around">
                    <div class="">
                        <?php foreach ($value->produtos as $k => $row) : ?>
                        <div class="my-1 d-flex flex-nowrap align-self-center">
                            <div class="order-1 p-2 align-self-center "><img
                                    src="assets/images/produtos/<?=$row->imagem ?> " alt="" class="img - fluid"
                                    width="50px"></div>
                            <div class="order-2 p-2 align-self-center">
                                <span><?=$row->nome_produto ?></span>
                            </div>
                            <div class=" order-3 p-2 align-self-center">
                                <span><?=formatarMoedaPtBr($row->preco_unidade) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="my-3 align-self-center">
                        <div class="">
                            <button class="btn btn-secondary" data-container="body" data-toggle="popover"
                                data-placement="top" onclick="verDetalheCompra(<?= $purchasing[$key]->id ?>)"
                                data-content="Top popover">Ver detalhes da compra</button>

                        </div>
                    </div>
                </div>
            </div>
            <br>
            <?php endforeach; ?>

            <?php else : ?>
            <div class="alert alert-info text-center my-3" role="alert">Você ainda não possui nenhuma compra realizada!
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>