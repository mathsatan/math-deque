<?php
if (!defined('MODEL_ARTICLES_PHP')){
    include 'app/models/modelarticles.php';
}
class ControllerUsers extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function action_sign_in() {
        try {
            $data['categories'] = ModelArticles::getCategories();
            $this->model = new ModelArticles();
            $this->model->getArticlesHeaders();
            $res = $this->model->getData();
            $data['articles_menu'] = $res['articles_menu'];
            unset($res);
            unset($this->model);
        } catch (PDOException $e) {
            $data['message'] = $e->getMessage();
        }

        parent::$session->start_session('_s', IS_HTTPS);

        $this->model = new ModelUsers();
        if($_POST['try_login'] == '1'){
            try {
                $captcha = ($_POST['checkReCaptcha'] === '1') ? $_POST['g-recaptcha-response'] : null;
                $this->model->signIn($_POST['login'], $_POST['password'], $captcha);
                $result = $this->model->getData();

                if ($result['is_success']){
                    $data['message'] = I_LOGIN_SUCCESS;
                    $data['msg_type'] = 'classic';
                    $_SESSION['user_id'] = $result['user_info']['user_id'];
                    $_SESSION['login'] = $result['user_info']['login'];
                    $_SESSION['user_pass'] = $result['user_info']['pass'];
                    header('Location:/main/index/msg/'.I_LOGIN_SUCCESS);
                }else{
                    $data['message'] = $result['error_msg'];
                    $data['msg_type'] = 'classic stick_error';
                }
            } catch (PDOException $e) {
                $data['message'] = $e->getMessage();
            }
        }
        $_SESSION['critical_attempts'] = $this->model->getAttemptsNumber(sprintf('%u', ip2long($_SERVER['REMOTE_ADDR'])));

        unset($this->model);
        $this->view->generate('loginview.php', 'templateview.php', $data);
    }

    public function action_sign_up() {
        try{
            $data['categories'] = ModelArticles::getCategories();
            $this->model = new ModelArticles();
            $this->model->getArticlesHeaders();
            $res = $this->model->getData();
            $data['articles_menu'] = $res['articles_menu'];
            unset($res);
        }catch (PDOException $e) {
            $data['message'] = $e->getMessage();
        }

        if(isset($_POST['try_reg']))
        {
            $data['message'] = '';
            try {
                $this->model = new ModelUsers();
                $this->model->signUp($_POST['login'], $_POST['password'], $_POST['password2'], $_POST['email'], $_POST['g-recaptcha-response']);
                $res = $this->model->getData();
                unset($this->model);

                if($res['is_success'] !== true){
                    $data['message'] = E_INSERT_FAIL;
                }

                if (!empty($data['message']))
                    $data['msg_type'] = 'classic stick_error';
                else{
                    $data['message'] = I_REG_SUCCESS;
                    $data['msg_type'] = 'classic';
                    header('Location:/main/index/msg/'.I_REG_SUCCESS);
                }
            } catch (PDOException $e1) {
                $data['message'] = $e1->getMessage();
            } catch (MVCException $e2) {
                $data['message'] = $e2->getMessage();
            }
        }
        $this->view->generate('regview.php', 'templateview.php', $data);
    }

    public function action_sign_out() {
        parent::$session->start_session('_s', IS_HTTPS);
        session_destroy();
        header('Location:/');
    }

    public function action_load_restore_pass() {
        try{
            $data['categories'] = ModelArticles::getCategories();
            $this->model = new ModelArticles();
            $this->model->getArticlesHeaders();
            $res = $this->model->getData();
            $data['articles_menu'] = $res['articles_menu'];
            unset($res);
            unset($this->model);
        }catch (PDOException $e1){
            $data['message'] = $e1->getMessage();
        }
        $this->view->generate('restoreview.php', 'templateview.php', $data);
    }

    public function action_restore_pass(){
        try{
            $data['categories'] = ModelArticles::getCategories();
            $this->model = new ModelArticles();
            $this->model->getArticlesHeaders();
            $res = $this->model->getData();
            $data['articles_menu'] = $res['articles_menu'];
            unset($this->model);
            unset($res);

            $this->model = new ModelUsers();
            $this->model->genNewPassword($_POST['restore_mail'], $_POST["g-recaptcha-response"]);
            $res = $this->model->getData();
            unset($this->model);
            if ($res['is_success'] === true){
                $data['msg_type'] = 'classic';
                $data['message'] = I_UPDATE_SUCCESS;
            }else{
                $data['message'] = E_UPDATE_FAIL;
            }
        }catch (PDOException $e1){
            $data['message'] = $e1->getMessage();
        }catch (MVCException $e2){
            $data['message'] = $e2->getMessage();
        }catch (TemplateException $e3){
            $data['message'] = $e3->getMessage();
        }
        $this->view->generate('loginview.php', 'templateview.php', $data);
    }
}
