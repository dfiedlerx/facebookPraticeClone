<?php
use Core\Engine\View;
use System\Models\Tools\Basic\GlobalValue;
?>

<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" content="notranslate">
        <title>Social</title>
        <?php
        View::loadCSSDependence('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', 'all', true);
        View::loadJSDependence('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js', true);
        View::loadJSDependence('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', true);
        View::loadJSDependence('https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js', true);
        ?>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">

            <div id="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <?php
                    $this->loadView(GlobalValue::get('template->navbarMenu'));
                    ?>
                </ul>
            </div>

        </nav>
        <div class="container">
            <?php
            $this->loadView(GlobalValue::get('template->action'));
            ?>
        </div>
    </body>

</html>
