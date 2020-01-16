<?php
use Core\Engine\View;
use System\Models\Tools\Basic\GlobalValue;
?>

<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Social</title>
        <?php
        View::loadCSSDependence('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', 'all', true);
        View::loadJSDependence('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', true);
        View::loadJSDependence('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js', true);
        ?>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">

            <div id="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php View::link(); ?>">Rede Social</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php View::link('login/entrar'); ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php View::link('login/cadastrar'); ?>">Cadastrar</a>
                    </li>
                </ul>
            </div>

        </nav>
        <div class="container">
            <?php
            $this->loadView('login/' . GlobalValue::get('login->action'));
            ?>
        </div>
    </body>

</html>
