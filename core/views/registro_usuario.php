<div class="container-fluid espaco_fundo_forms">
    <div class="row my-5">
        <div class="col-sm-6 offset-sm-3">
            <h3 class="text-center">Registro de Novo Usuário</h3>

            <form action="?r=criar_conta" method="post">
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" name="name" value="<?php echo isset($name) && !empty($name) ? $name : "" ?>" placeholder="Informe seu Nome Completo" class="form-control" >
                </div>
                <div class="form-group">
                    <label for="name">E-mail</label>
                    <input type="email" name="email" value="<?php echo isset($email) && !empty($email) ? $email : "" ?>" placeholder="Informe o seu E-mail" class="form-control" >
                </div>
                <div class="form-group">
                    <label for="name">Senha</label>
                    <input type="password" name="password" value="<?php echo isset($password) && !empty($password) ? $password : "" ?>" placeholder="Informe a sua Senha" class="form-control" >
                </div>
                <div class="form-group">
                    <label for="name">Repita a Senha</label>
                    <input type="password" name="password_repeat" placeholder="Repita a sua Senha" class="form-control" >
                </div>
                <div class="form-group">
                    <input type="submit" value="Criar Conta" class="btn btn-primary" />
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" name="ip" value="<?php echo($_SERVER['REMOTE_ADDR'])?>" readonly="true">
                </div>

                <?php if (isset($_SESSION['erro'])) : ?>
                    <div class="form-group alert alert-danger text-center p-2">
                        <?= $_SESSION['erro'] ?>
                        <?php unset($_SESSION['erro']) ?>
                    </div>
                <?php elseif (isset($_SESSION['sucesso'])) : ?>
                    <div class="form-group alert alert-success text-center p-2">
                        <?= $_SESSION['sucesso'] ?>
                        <?php unset($_SESSION['sucesso']) ?>
                    </div>
                <?php endif; ?>
            </form>

        </div>
    </div>
</div>
