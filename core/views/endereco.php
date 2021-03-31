<div class="container-fluid container-endereco">
    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-6 offset-sm-3 offset-md-3 offset-lg-3">
            <h3 class="text-center">Cadastro de Endereço</h3>
            <form action="" method="post" id="form-cad-end">
                <div class="d-flex flex-row form-group">
                    <div class="col-md-8 col-sm-3 col-lg-8">
                        <label for="public_place_id" class="col-md-6 control-label">Logradouro</label>
                        <select class="form-control" name="public_place_id" id="public_place_id"
                            data-placeholder="Selecione a cidade">
                            <option value="" disabled selected hidden>Selecione a cidade</option>
                            <?php if (isset($dados['logradouros'])) : ?>
                            <?php foreach ($dados['logradouros'] as $value) : ?>
                            <?="<option value='" . $value->id . "'>" . $value->logradouro . "</option>"?>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-3 col-lg-4">
                        <label for="tipo_endereco" class="col-md-6 control-label">Tipo</label>
                        <select class="form-control" name="tipo_endereco" id="tipo_endereco"
                            data-placeholder="Selecione o Tipo de Endereço">
                            <option value="" disabled selected hidden>Tipo de Endereço</option>
                            <option value="1">Residencial</option>
                            <option value="2">Trabalho</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex flex-row form-group">
                    <div class="col-md-8 col-sm-3 col-lg-8">
                        <label for="complemento" class="col-md-6 control-label">Complemento</label>
                        <input type="text" name="complemento" id="complemento" class="form-control"
                            placeholder="Informe o Complemento do Endereço" />
                    </div>
                    <div class="col-md-4 col-sm-3 col-lg-4">
                        <label for="numero" class="col-md-6 control-label">Número</label>
                        <input type="text" name="numero" id="numero" class="form-control"
                            placeholder="Informe o Número" />
                    </div>
                </div>
                <div class="d-flex flex-row form-group">
                    <div class="col-md-5 col-sm-3 col-lg-5">
                        <label for="bairro" class="col-md-6 control-label">Bairro</label>
                        <input type="text" name="bairro" id="bairro" class="form-control"
                            placeholder="Informe o Bairro" />
                    </div>
                    <div class="col-md-3 col-sm-3 col-lg-3">
                        <label for="cep" class="col-md-6 control-label">Cep</label>
                        <input type="text" name="cep" id="cep" class="form-control"
                            onkeypress="$(this).mask('00.000-000')" placeholder="Informe o Cep" />
                    </div>
                    <div class="col-md-4 col-sm-3 col-lg-4">
                        <label for="uf" class="col-md-6 control-label">Uf</label>
                        <select class="form-control" name="uf" id="uf" data-placeholder="Selecione a Uf">
                            <option value="" disabled selected hidden>Selecione a UF</option>
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AP">AP</option>
                            <option value="AM">AM</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MT">MT</option>
                            <option value="MS">MS</option>
                            <option value="MG">MG</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PR">PR</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RS">RS</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SP">SP</option>
                            <option value="SE">SE</option>
                            <option value="TO">TO</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex flex-row">
                    <div class="col-md-3 col-sm-3 col-lg-3 my-3">
                        <input type="submit" value="Cadastrar" class="btn btn-primary" id="btn-cadastrar-end" />
                    </div>
                    <div class="col-md-8 col-sm-3 col-lg-8 alert alert-danger my-2 text-center msgError" role="alert">
                    </div>
                    <div class="col-md-8 col-sm-3 col-lg-8 alert alert-success my-2 text-center msgSuccess"
                        role="alert"></div>
                </div>
            </form>
        </div>
    </div>
</div>