<?php
use Core\Engine\View;
use System\Models\Tools\Basic\GlobalValue;
?>

<li class="nav-item">
    <a class="nav-link" href="<?php View::link(); ?>">Rede Social</a>
</li>

<li class="dropdown">
    <a class="dropdown-toggle nav-link" data-toggle="dropdown" href="#">
        <?php View::value(GlobalValue::get('user->nome')) ?>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <li>
            <a class="nav-link" href="<?php View::link('perfil'); ?>">
                Editar Perfil
            </a>
        </li>
        <li>
            <a class="nav-link" href="<?php View::link('home/sair'); ?>">Sair</a>
        </li>
    </ul>
</li>