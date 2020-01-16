<?php
use Core\Engine\View;
use System\Models\Tools\Basic\GlobalValue;
?>

<h1>Cadastrar</h1>

<form method="post">

    <?php
    if (GlobalValue::exists('view->message')) {
        ?>
        <div class="alert alert-danger">
            <?php View::value(GlobalValue::get('view->message')) ?>
        </div>
        <?php
    }
    ?>

    <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome"/>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" id="email"/>
    </div>

    <div class="form-group">
        <label for="senha">Senha</label>
        <input type="password" class="form-control" name="senha" id="senha"/>
    </div>

    <div class="form-group">
        <label>
            Masculino
            <input type="radio" class="form-control" name="sexo" value="1" checked/>
        </label>
        <label>
            Feminino
            <input type="radio" class="form-control" name="sexo" value="1"/>
        </label>
    </div>

    <button type="submit" class="btn btn-primary">Cadastrar</button>

</form>

