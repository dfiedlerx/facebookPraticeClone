<?php

use Core\Engine\View;
use System\Models\Tools\Basic\GlobalValue;
?>

<h1>Login</h1>

<form method="post">

    <?php
    if (GlobalValue::exists('view->error')) {
    ?>
    <div class="alert alert-danger">
        <?php View::value(GlobalValue::get('view->error')) ?>
    </div>
    <?php
    }
    ?>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" id="email"/>
    </div>

    <div class="form-group">
        <label for="senha">Senha</label>
        <input type="password" class="form-control" name="senha" id="senha"/>
    </div>

    <button type="submit" class="btn btn-primary">Entrar</button>

</form>

