<div class="container container-compra-confirmada">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h4 class="text-center">Compra Confirmada</h4>

            <p>Muito obrigado pela sua compra.</p>
            <div class="my-5 text-center">
                <h5>Dados do Pagamento</h5>
                <p>Conta Bancária: 123456</p>
                <p>Código da Compra: <strong><?=$codigo_compra?></strong></p>
                <p>Valor Total: <strong>R$ <?=$total_compra?></strong></p>
            </div>

            <p>Por favor, verifique se o e-mail chegou em sua conta ou se foi para a pasta de SPAM.</br>
               A sua compra só será processada após confirmação do pagamento.</p></br>

            <a href="?r=inicio" onclick="" class="btn btn-primary">Voltar</a>   
        </div>
    </div>
</div>
