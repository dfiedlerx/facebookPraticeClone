<?php namespace System\Controllers\Perfil;


use Core\Engine\Controller;
use System\Models\Tools\Basic\GlobalValue;
use System\Models\Tools\Basic\Session;

session_start();

class PerfilController extends Controller
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

        GlobalValue::set('perfil/index', 'template->action');
        return $this->loadView ('template');

    }

}