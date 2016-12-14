<?php
if (!file_exists('app/models/modelarticles.php') || !file_exists('app/models/modelusers.php')) {
    throw new MVCException(E_MODEL_FILE_DOESNT_EXIST);
}
include 'app/models/modelarticles.php';
include 'app/models/modelusers.php';

class ControllerAdmin extends Controller
{
    private $data = null;

    public function __construct() {
        parent::__construct();
        $this->model = new ModelUsers();
        if (!$this->model->isCurrentUserAdmin($_SESSION['user_id'], $_SESSION['user_pass'])) {
            throw new MVCException(E_NOT_ALLOWED);
        }
        unset($this->model);
    }

        // Список пользователей
	public function action_index(){
        try {
            $this->data['users_count'] = ModelUsers::getUserCount();
            if($this->page === false || $this->page >= ceil($this->data['users_count']/USERS_COUNT)){
                $this->page = 0;
            }
            $this->model = new ModelUsers();
            $this->model->getUsers(USERS_COUNT, $this->page*USERS_COUNT);
            $res = $this->model->getData();
            $this->data['users'] = $res['users'];
            unset($res);
            unset($this->model);
        }catch (PDOException $e1){
            throw $e1;
        }catch (MVCException $e2){
            throw $e2;
        }
        $this->view->generate('user_management.php', 'admintemplateview.php',  $this->data);
	}

            // Обновить пользователя
    public function action_load_update_form() {
        try {
            $this->model = new ModelUsers();
            $this->model->getUserInfo($this->user_id);
            $res = $this->model->getData();
            $this->data['user_info'] = $res['user_info'];
            unset($this->model);

        }catch (PDOException $e1){
            throw $e1;
        }

        if (!empty($this->data['user_info'])) {
            $this->view->generate('update_form.php', 'admintemplateview.php', $this->data);
        }else{
            $this->data['message'] = E_USER_NOT_FOUND;
            $this->action_index();
        }
    }
    public function action_update_user() {
        try {
            $this->model = new ModelUsers();
            ($_POST['new_is_active'] == 'on') ? $isActive = 1 : $isActive = 0;
            $this->model->updateUser($this->user_id, $_POST['new_login'], $_POST['new_pass'],
                $_POST['new_email'], $_POST['new_status'], $isActive);
            $res = $this->model->getData();
            unset($this->model);
        }catch (PDOException $e1){
            throw $e1;
        }catch (MVCException $e2){
            throw $e2;
        }

        if ($res['is_success'] === true) {
            $this->data['message'] = I_UPDATE_SUCCESS;
            $this->data['msg_type'] = 'classic';
        }else{
            $this->data['message'] = E_UPDATE_FAIL;
        }
        $this->action_index();
    }

            // Добавить нового пользователя
    public function action_load_insert_form() {
        $this->view->generate('insert_form.php', 'admintemplateview.php', $this->data);
    }

    public function action_insert_user() {
        try {
            $this->model = new ModelUsers();
            ($_POST['is_active'] == 'on') ? $isActive = 1 : $isActive = 0;
            $this->model->signUp($_POST['login'], $_POST['pass'], $_POST['email'], $_POST['status'], $isActive);

            $res = $this->model->getData();
            unset($this->model);

        }catch (PDOException $e1){
            throw $e1;
        }catch (MVCException $e2){
            throw $e2;
        }

        if ($res['is_success'] === true) {
            $this->data['message'] = I_INSERT_SUCCESS;
            $this->data['msg_type'] = 'classic';
        }else{
            $this->data['message'] = E_INSERT_FAIL;
        }
        $this->action_index();
    }
            // Удалить пользователя
    public function action_delete_user() {
        try {
            $this->model = new ModelUsers();
            $this->model->deleteUser($this->user_id);
            $res = $this->model->getData();
            unset($this->model);
        }catch (PDOException $e1){
            throw $e1;
        }catch (MVCException $e2){
            throw $e2;
        }

        if ($res['is_success'] === true) {
            $this->data['message'] = I_DELETE_SUCCESS;
            $this->data['msg_type'] = 'classic';
        }else{
            $this->data['message'] = E_DELETE_FAIL;
        }
        $this->action_index();      //    что бы нельзя было повторно отправить
    }

    public function action_articles_list() {
        try {
            $this->model = new ModelArticles();
            $this->data['articles_count'] = $this->model->getArticlesNumber();
            if($this->page === false || $this->page >= ceil($this->data['articles_count']/ARTICLES_COUNT)){
                $this->page = 0;
            }
            $this->model->getArticles(ARTICLES_COUNT, $this->page*ARTICLES_COUNT);
            $res = $this->model->getData();
            $this->data['articles'] = $res['articles'];
            unset($res);
            unset($this->model);
        }catch (PDOException $e1){
            throw $e1;
        }catch (MVCException $e2){
            throw $e2;
        }
        $this->view->generate('article_management.php', 'admintemplateview.php',  $this->data);
    }
            // Удалить статью
    public function action_delete_article() {
        try {
            $this->model = new ModelArticles();
            $this->model->deleteArticle($this->article_id);
            $res = $this->model->getData();
            unset($this->model);
        }catch (PDOException $e){
            throw $e;
        }
        if ($res['is_success'] === true) {
            $this->data['message'] = I_DELETE_SUCCESS;
            $this->data['msg_type'] = 'classic';
        }else{
            $this->data['message'] = E_DELETE_FAIL;
        }
        $this->action_articles_list();
    }
            // Обновить статью
    public function action_load_update_article_form() {
        try {
            $this->model = new ModelArticles();
            $this->model->getArticleById($this->article_id);
            $res = $this->model->getData();
            $this->data['article'] = $res['article'];
            $this->data['categories'] = ModelArticles::getCategories();
            unset($res);
            unset($this->model);
            $this->model = new ModelUsers();
            $this->model->getUserInfo($this->data['article']['user_id']);
            $user = $this->model->getData();
            $this->data['author'] = $user['user_info'];
            unset($this->model);

            if (!empty($this->data['article'])) {
                $this->view->generate('update_article_form.php', 'admintemplateview.php', $this->data);
            }else{
                $this->data['message'] = E_ARTICLES_NOT_FOUND;
                $this->action_index();
            }
        }catch (PDOException $e1){
            throw $e1;
        }catch (MVCException $e2){
            throw $e2;
        }catch (TemplateException $e3){
            throw $e3;
        }
    }

    public function action_update_article() {
        try {
            $this->model = new ModelArticles();
            $this->model->updateArticle($this->article_id, $_POST['new_title'], $_POST['new_text'], $_POST['new_cat'], $_POST['article_date'], $_POST['new_str_id'], $_POST['new_tags'], $_POST['new_desc']);
            $res = $this->model->getData();
            unset($this->model);
        }catch (PDOException $e1){
            throw $e1;
        }catch (MVCException $e2){
            throw $e2;
        }

        if ($res['is_success'] === true) {
            $this->data['message'] = I_UPDATE_SUCCESS;
            $this->data['msg_type'] = 'classic';
        }else{
            $this->data['message'] = E_UPDATE_FAIL;
        }
        $this->action_articles_list();
    }

            // Добавить новую статью
    public function action_load_insert_article_form() {
        try{
            $this->data['categories'] = ModelArticles::getCategories();
        }catch (PDOException $e){
            throw $e;
        }
        $this->view->generate('insert_article_form.php', 'admintemplateview.php', $this->data);
    }

    public function action_insert_article() {
        try {
            $this->model = new ModelArticles();
            $this->model->insertArticle($_POST['article_title'], $_POST['article_text'], $_POST['article_cat'], $_POST['article_date'], $_POST['user_id'], $_POST['str_article_id'], $_POST['article_tags'], $_POST['article_desc']);
            $res = $this->model->getData();
            unset($this->model);
        }catch (PDOException $e1){
            throw $e1;
        }catch (MVCException $e2){
            throw $e2;
        }
        if ($res['is_success'] === true) {
        $this->data['message'] = I_INSERT_SUCCESS;
        $this->data['msg_type'] = 'classic';
        }else{
            $this->data['message'] = E_INSERT_FAIL;
        }
            $this->action_articles_list();
    }

    public function action_media_list() {
        try{
            $this->data['pics_count'] = ModelAdmin::getImgCount();
            if($this->page === false || $this->page >= ceil($this->data['pics_count']/IMG_COUNT)){
                $this->page = 0;
            }
            $this->data['pics'] = ModelAdmin::getImages(IMG_COUNT, $this->page*IMG_COUNT);

        }catch (PDOException $e){
            throw $e;
        }
        $this->view->generate('media_list.php', 'admintemplateview.php', $this->data);
    }

    public function action_load_insert_pic(){
        $this->view->generate('insert_pic.php', 'admintemplateview.php', $this->data);
    }

    public function action_insert_pic(){
        try {
            $this->model = new ModelAdmin();
            if ($_POST['r1'] === 'url'){
                $this->model->loadImage($_POST['pic_desc'], $_POST['align_type'], $_POST['tag_alt'], $_POST['article_image_url']);
            }else{
                $this->model->loadImage($_POST['pic_desc'], $_POST['align_type'], $_POST['tag_alt']);
            }
            $res = $this->model->getData();
            unset($this->model);

            if ($res['is_success'] === true) {
                $this->data['message'] = I_INSERT_SUCCESS;
                $this->data['msg_type'] = 'classic';
                $this->action_media_list();
                exit;
            }else{
                $this->data['message'] = E_INSERT_FAIL;
            }
        }catch (PDOException $e1){
            $this->data['message'] = $e1->getMessage();
        }catch (MVCException $e2){
            $this->data['message'] = $e2->getMessage();
        }
        $this->view->generate('insert_pic.php', 'admintemplateview.php', $this->data);
    }

    public function action_delete_pic(){
        try {
            $this->model = new ModelAdmin();
            $this->model->deleteImage($this->pic_id);
            $res = $this->model->getData();
            unset($this->model);
            if ($res['is_success'] === true) {
                $this->data['message'] = I_DELETE_SUCCESS;
                $this->data['msg_type'] = 'classic';
            }else{
                $this->data['message'] = E_DELETE_FAIL;
            }
        }catch (PDOException $e){
            $this->data['message'] = $e->getMessage();
        }
        $this->action_media_list();
    }

    public function action_load_update_pic(){
        try {
            $this->data['pic_info'] = ModelAdmin::getImageById($this->pic_id);
        }catch (PDOException $e){
            throw $e;
        }
        $this->view->generate('update_pic.php', 'admintemplateview.php', $this->data);
    }

    public function action_update_pic() {
        try {
            $this->model = new ModelAdmin();
            $this->model->updateImage($this->pic_id, $_POST['new_desc'], $_POST['new_align'], $_POST['new_alt']);
            $res = $this->model->getData();
            unset($this->model);

            if ($res['is_success'] === true) {
                $this->data['message'] = I_UPDATE_SUCCESS;
                $this->data['msg_type'] = 'classic';
            }else{
                $this->data['message'] = E_UPDATE_FAIL;
            }

        }catch (PDOException $e1){
            $this->data['message'] = $e1->getMessage();
        }catch (MVCException $e2){
            $this->data['message'] = $e2->getMessage();
        }

        $this->action_media_list();
    }
}