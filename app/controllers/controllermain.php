<?php
if (file_exists('app/models/modelarticles.php')) {
    if (!defined('MODEL_ARTICLES_PHP')) include 'app/models/modelarticles.php';
} else {
    throw new MVCException(E_MODEL_FILE_DOESNT_EXIST);
}

class ControllerMain extends Controller
{
    public function __construct() {
        parent::__construct();
    }

	public function action_index(){
        if ($this->msg !== false) {
            $data['message'] = $this->msg;
            $data['msg_type'] = 'classic';
        } else $data['message'] = '';

        try {
            $this->model = new ModelArticles();
            $this->model->getArticles(NEWS_COUNT);
            $res = $this->model->getData();
            $data['articles'] = $res['articles'];
            $data['categories'] = ModelArticles::getCategories();
            $this->model->getArticlesHeaders();
            $res = $this->model->getData();
            $data['articles_menu'] = $res['articles_menu'];
            unset($res);
            unset($this->model);
        } catch (PDOException $e) {
            $data['message'] = $e->getMessage();
        }
        $this->view->generate('mainview.php', 'templateview.php', $data);
	}

    public function action_change_lang()
    {
        $_SESSION['lang'] = 'en';
        $this->view->generate('mainview.php', 'templateview.php');
    }

    public function action_order() {
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
        $this->view->generate('mainview/order_work.htx', 'templateview.php', $data);
    }

    public function action_about()
    {
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
        $this->view->generate('mainview/about.htx', 'templateview.php', $data);
    }
}