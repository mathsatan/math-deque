<?php
if (file_exists('app/lib/secure/session.php')) {
    if (!defined('SESSION_PHP')) include 'app/lib/secure/session.php';
}else {
    throw new MVCException(E_CLASS_NOT_FOUND.': Session');
}
if (file_exists('app/lib/secure/filterdata.php')) {
    if (!defined('FILTER_DATA_PHP')) include 'app/lib/secure/filterdata.php';
}else {
    throw new MVCException(E_CLASS_NOT_FOUND.': FilterData');
}

class Controller {
    protected $model;
    protected $view;
    protected $params;

    protected static $session;  // secure session instance

    public function __construct(){
        self::$session = new Session();
        self::$session->start_session('_s', IS_HTTPS);     // Set to true if using https

        if (isset($_POST['lang']))
            $_SESSION['lang'] = $_POST['lang'];
        elseif (!isset($_SESSION['lang']))
            $_SESSION['lang'] = 'ru';

        require 'app/lang/'. $_SESSION['lang'].'.php';
        $this->view = new View();
    }

    public function action_index(){
    }

    public function addParams($parameters){
        if(!empty($parameters)){
            $this->params = $parameters;
        }
    }

    public function __get($param){
        if (isset($this->params[$param])) {
            return $this->params[$param];
        }
        return false;
    }

} 