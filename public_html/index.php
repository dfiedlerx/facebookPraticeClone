<?php

use Core\Engine\Core;

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// Auto Loader que chamara as classes do sistema.
require dirname(__FILE__) . '/../app/vendor/autoload.php';

//Arquivo que carregará as configurações
require  dirname(__FILE__) . '/../app/core/init/config.php';

//Inicia a engrenagem da arquitetura
(new Core())->run();
