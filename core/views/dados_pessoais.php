<div class="container-fluid container-dados-pessoais col-sm-12 col-md-12 col-lg-12">
    <div class="row my-4">
        <div class="col-sm-8 col-md-8 col-lg-8 offset-sm-2 offset-md-2 offset-lg-2">
            <h3 class="text-center">Dados Pessoais</h3>
            <div class="jumbotron col-sm-12 col-md-12 col-lg-12">
                <?php if (isset($dados)) : ?>
                <form id="form-update-user" method="post" action="">
                    <div class="form-row">
                        <div class="col-md-6 col-sm-6 col-lg-6 mb-3">
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control" id="nome" value="<?=$dados['user']->name ?>">
                            <input type="hidden" name="_csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 mb-3">
                            <label for="email">E-mail:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="">@</span>
                                </div>
                                <input type="email" class="form-control" id="email" aria-describedby=""
                                    value="<?=$dados['user']->email ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3 col-sm-3 col-lg-3 mb-3">
                            <label for="public_place_id">Endereço:</label>
                            <select class="form-control" name="public_place_id" id="public_place_id">
                                <option value="<?=$dados['user']->id_logradouro?>"><?=$dados['user']->logradouro?></option>
                                <?php if (isset($dados['logradouros'])) : ?>
                                <?php foreach ($dados['logradouros'] as $value) : ?>
                                <?="<option value='" . $value->id . "'>" . $value->logradouro . "</option>"?>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 mb-3">
                            <label for="complemento">Complemento:</label>
                            <input type="text" class="form-control" id="complemento"
                                value="<?=$dados['user']->complemento?>">
                        </div>
                        <div class="col-md-3 col-sm-3 col-lg-3 mb-3">
                            <label for="numero">Número:</label>
                            <input type="text" class="form-control" id="numero" value="<?=$dados['user']->numero ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 col-sm-4 col-lg-4 mb-3">
                            <label for="bairro">Bairro:</label>
                            <input type="text" class="form-control" id="bairro" value="<?=$dados['user']->bairro ?>">
                        </div>
                        <div class="col-md-3 col-sm-3 col-lg-3 mb-3">
                            <label for="cep">Cep:</label>
                            <input type="text" class="form-control" id="cep"
                                value="<?=formataCep($dados['user']->cep) ?>">
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 mb-3">
                            <label for="uf">Uf:</label>
                            <select class="form-control" id="uf" id="tipo_endereco">
                                <option value="DF"><?=$dados['user']->uf ?></option>
                                <option value="AC">AC</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-3 col-lg-3 mb-3">
                            <label for="tipo_endereco">Tipo Endereço:</label>
                            <select class="form-control" name="tipo_endereco" id="tipo_endereco">
                                <option value="<?=$dados['user']->tipo_endereco == "Residencial" ? 1 : 2?>"><?=$dados['user']->tipo_endereco?></option>
                                <option value="1">Residencial</option>
                                <option value="2">Trabalho</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="col-md-3 col-sm-3 col-lg-3 my-3">
                            <input type="submit" value="Enviar" class="btn btn-primary" id="btn-update-user" />
                        </div>
                        <div class="col-md-8 col-sm-3 col-lg-8 alert alert-danger my-2 text-center msgError"
                            role="alert">
                        </div>
                        <div class="col-md-8 col-sm-3 col-lg-8 alert alert-success my-2 text-center msgSuccess"
                            role="alert"></div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>