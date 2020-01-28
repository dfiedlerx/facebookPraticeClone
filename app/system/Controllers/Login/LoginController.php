<?php namespace System\Controllers\Login;


use Core\Engine\Controller;
use System\Models\Instances\Usuarios;
use System\Models\Tools\Basic\Crypto;
use System\Models\Tools\Basic\Filter;
use System\Models\Tools\Basic\GlobalValue;
use System\Models\Tools\Basic\Session;
use System\Models\Tools\Basic\Url;

session_start();

class LoginController extends Controller
{

    public function __construct() {

        parent::__construct();
        Session::controlLoginSessionPage('lgsocial', false);
        GlobalValue::set('login/menu', 'template->navbarMenu');

    }

    public function index() {

        if (Filter::externalValuesExists(INPUT_POST, ['email', 'senha'])) {

            $email = Filter::externalFilter
            (
                INPUT_POST,
                'email',
                FILTER_SANITIZE_EMAIL
            );

            $password =
                Filter::externalFilter
                (
                    INPUT_POST,
                    'senha',
                    FILTER_SANITIZE_STRING
                );

            if (Usuarios::login($email, $password)) {

                Url::redirect(LOGGED_ROUTE);

            } else {

                GlobalValue::set('Email ou senha incorretos!', 'view->error');

            }

        }

        GlobalValue::set('login/index','template->action');

        return $this->loadView('template');

    }

    public function cadastrar () {

        if (
        Filter::externalValuesExists
        (
            INPUT_POST,
            [
                'nome',
                'email',
                'senha',
                'sexo'
            ]
        )
        ) {
            $nome =
                Filter::externalFilter
                (
                    INPUT_POST,
                    'nome',
                    FILTER_SANITIZE_STRING
                );

            $email = Filter::externalFilter
            (
                INPUT_POST,
                'email',
                FILTER_SANITIZE_EMAIL
            );

            $password =
                Crypto::passwordHashGenerator(
                    Filter::externalFilter
                    (
                        INPUT_POST,
                        'senha',
                        FILTER_SANITIZE_STRING
                    )
                );

            $sexo =
                Filter::externalFilter
                (
                    INPUT_POST,
                    'sexo',
                    FILTER_SANITIZE_NUMBER_INT
                );

            if (Usuarios::register($nome, $email, $password, $sexo)) {

                Url::redirect(LOGGED_ROUTE);

            }

        }

        GlobalValue::set('login/cadastrar','template->action');

        return $this->loadView('template');

    }

}