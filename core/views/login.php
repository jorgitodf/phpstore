<div class="container-fluid container-login">
    <div class="row my-5">
        <div class="col-sm-4 offset-sm-4">
            <h3 class="text-center">Login de Cliente</h3>

            <form action="?r=logar" method="post">
                <div class="form-group">
                    <label for="name">E-mail:</label>
                    <input type="email" name="email" value="<?php echo isset($email) && !empty($email) ? $email : "" ?>"
                        placeholder="Informe o seu E-mail para Logar no Sistema" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Senha:</label>
                    <input type="password" name="password"
                        value="<?php echo isset($password) && !empty($password) ? $password : "" ?>"
                        placeholder="Informe a sua Senha para Logar no Sistema" class="form-control">
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" name="ip" value="<?php echo($_SERVER['REMOTE_ADDR'])?>"
                        readonly="true">
                </div>

                <div class="form-group">
                    <input type="submit" value="Logar" class="btn btn-primary" />
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