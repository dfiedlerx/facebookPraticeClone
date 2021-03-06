<?php namespace System\Controllers\Home;


use Core\Engine\Controller;
use System\Models\Home\HomeMailer;
use System\Models\Instances\Usuarios;
use System\Models\Tools\Basic\GlobalValue;
use System\Models\Tools\Basic\Session;
use System\Models\Tools\Basic\Url;

session_start();

/**
 * Class HomeController
 * @package System\Controllers\Home
 */
class HomeController extends Controller
{

    public function __construct() {

        parent::__construct();
        Session::controlLoginSessionPage('lgsocial');

        GlobalValue::set('home/menu', 'template->navbarMenu');
        GlobalValue::set($_SESSION['lgsocial'], 'user');

    }

    /**
     * @return bool
     */
    public function index () {

        GlobalValue::set('home/home', 'template->action');
        return $this->loadView ('template');

    }

    public function sair () {

        Usuarios::logout();
        Url::redirect(NOT_LOGGED_ROUTE);

    }

}
