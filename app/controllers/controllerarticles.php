<?php

class ControllerArticles extends Controller
{
    private $data = null;
    public function __construct(){
        parent::__construct();
        $this->data = array();
        $this->data['msg_type'] = 'stick error';
    }

    public function action_article_by_cat()
    {
        try {
            $this->model = new ModelArticles();
            $this->data['categories'] = ModelArticles::getCategories();
            $this->data['current_cat'] = ModelArticles::getCategories($this->cat_id);

            $this->model->getArticlesHeaders();
            $res = $this->model->getData();
            $this->data['articles_menu'] = $res['articles_menu'];
            unset($res);
            unset($this->model);
        } catch (PDOException $e1) {
            $this->data['message'] = $e1->getMessage();
        } catch (MVCException $e2) {
            $this->data['message'] = $e2->getMessage();
        }
        $this->view->generate('articleslistview.php', 'templateview.php', $this->data);
    }

    public function action_add_comment()
    {
        if (!empty($_POST['comment_text'])) {
            try {
                $this->model = new ModelArticles();
                $this->model->addComment($_POST['user_id'], $_POST['article_id'], $_POST['comment_text']);
                unset($this->model);
            } catch (PDOException $e1) {
                $this->data['message'] = $e1->getMessage();
            } catch (MVCException $e2) {
                $this->data['message'] = $e2->getMessage();
            }
        }
        if (!empty($this->data['message'])){
            $this->action_index($_POST['article_id']);
        }else{
            (LINKS_TYPE === 1) ? $articleId = $_POST['str_article_id'] : $articleId = $_POST['article_id'];
            header('Location:/articles/index/article_id/'.$articleId.'#pointer');
        }
    }

    public function action_delete_comment()
    {
        try {
            $this->model = new ModelArticles();
            $this->model->deleteComment($this->comment_id);
            unset($this->model);
        } catch (PDOException $e1) {
            $this->data['message'] = $e1->getMessage();
        } catch (MVCException $e2) {
            $this->data['message'] = $e2->getMessage();
        }
        if (!empty($this->data['message'])){
            $this->action_index($this->article_id);
        }else {
            (LINKS_TYPE === 1) ? $currId = $_POST['str_article_id'] : $currId = $this->article_id;
            header('Location:/articles/index/article_id/' . $currId /*$articleId*/ . '#pointer');
        }
    }

    public function action_index($articleId = null){
        try {
            $this->model = new ModelArticles();
            if ($articleId === null){
                $this->model->getArticleById($this->article_id);
            }else{
                $this->model->getArticleById($articleId);
            }
            $this->data['article_data'] = $this->model->getData();
            $this->data['categories'] = ModelArticles::getCategories();
            $this->model->getArticlesHeaders();
            $res = $this->model->getData();
            $this->data['articles_menu'] = $res['articles_menu'];
            unset($res);
            unset($this->model);
        }catch (PDOException $e1) {
            $this->data['message'] = $e1->getMessage();
        }catch (MVCException $e2) {
            $this->data['message'] = $e2->getMessage();
        }
        $this->view->generate('articlesview.php', 'templateview.php', $this->data);
    }
}