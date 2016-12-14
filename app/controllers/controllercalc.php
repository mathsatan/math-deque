<?php
if (file_exists('app/models/modelarticles.php')) {
    if (!defined('MODEL_ARTICLES_PHP')) include 'app/models/modelarticles.php';
} else {
    throw new MVCException(E_MODEL_FILE_DOESNT_EXIST);
}

class ControllerCalc extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function action_index() {
        $data['categories'] = ModelArticles::getCategories();
        $this->model = new ModelArticles();
        $this->model->getArticlesHeaders();
        $res = $this->model->getData();
        $data['articles_menu'] = $res['articles_menu'];
        unset($res);
        unset($this->model);

        $this->view->generate('calcview.php', 'templateview.php', $data);
    }
} 