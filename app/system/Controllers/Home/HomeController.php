<?php namespace System\Controllers\Home;


use Core\Engine\Controller;
use System\Models\Home\HomeMailer;
use System\Models\Tools\Basic\Session;

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

    }

    /**
     * @return bool
     */
    public function index () {

        return $this->loadView ('home/home');

    }

}
