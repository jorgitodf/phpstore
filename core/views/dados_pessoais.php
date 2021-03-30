<div class="container-fluid container-dados-pessoais col-sm-12 col-md-12 col-lg-12">
    <div class="row my-4">
        <div class="col-sm-8 col-md-8 col-lg-8 offset-sm-2 offset-md-2 offset-lg-2">
            <h3 class="text-center">Dados Pessoais</h3>

            <div class="jumbotron col-sm-12 col-md-12 col-lg-12">
                <?php if (isset($dados)) : ?>
                <form>
                    <div class="form-group row">
                        <label for="nome" class="col-sm-2 col-md-2 col-lg-2 col-form-label negrito">Nome:</label>
                        <div class="col-sm-10 col-md-10 col-lg-10">
                            <input type="text" readonly class="form-control-plaintext" id="nome"
                                value="<?=$dados->name ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-md-2 col-lg-2 col-form-label negrito">Email:</label>
                        <div class="col-sm-10 col-md-10 col-lg-10">
                            <input type="text" readonly class="form-control-plaintext" id="email"
                                value="<?=$dados->email ?>">
                        </div>
                    </div>
                    <div class="form-group row d-flex flex-row">
                        <label for="endereco"
                            class="col-sm-2 col-md-2 col-lg-2 col-form-label negrito">Endereço:</label>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <input type="text" readonly class="form-control-plaintext" id="endereco"
                                value="<?=$dados->logradouro . " " . $dados->complemento . " " . $dados->numero ?>">
                        </div>
                        <label for="tipo_endereco" class="col-sm-3 col-md-3 col-lg-3 col-form-label negrito">Tipo de
                            Endereço:</label>
                        <div class="col-sm-3 col-md-3 col-lg-3">
                            <input type="text" readonly class="form-control-plaintext" id="tipo_endereco"
                                value="<?=$dados->tipo_endereco ?>">
                        </div>
                    </div>
                    <div class="form-group row d-flex flex-row">
                        <label for="bairro" class="col-sm-2 col-md-2 col-lg-2 col-form-label negrito">Bairro:</label>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <input type="text" readonly class="form-control-plaintext" id="bairro"
                                value="<?=$dados->bairro ?>">
                        </div>
                    </div>
                    <div class="form-group row d-flex flex-row">
                        <label for="cep" class="col-sm-2 col-md-2 col-lg-2 col-form-label negrito">Cep:</label>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <input type="text" readonly class="form-control-plaintext" id="cep"
                                value="<?=$dados->cep ?>">
                        </div>
                        <label for="uf" class="col-sm-1 col-md-2 col-lg-1 col-form-label negrito">Uf:</label>
                        <div class="col-sm-3 col-md-3 col-lg-3">
                            <input type="text" readonly class="form-control-plaintext" id="uf" value="<?=$dados->uf ?>">
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>