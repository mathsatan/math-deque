<?php
define('MODEL_ARTICLES_PHP', 0);

class ModelArticles extends Model
{
    private $RESULT = null;
    //private $orders = array('ASC', 'DESC');

    public function addComment($userId, $artId, $text){
        if (!FilterData::isCorrect($userId, FilterData::CHECK_ID) ||
            !FilterData::isCorrect($artId, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID);
        }
        if (empty($text)){
            throw new MVCException(E_EMPTY_FIELD);
        }

        try {   // SET time_zone = '+03:00' SET GLOBAL time_zone = '+03:00'
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("INSERT INTO mvc_comments (comment_id, user_id, article_id, comment_text, comment_date) VALUES (NULL, :user_id, :article_id, :comment_text, CURRENT_TIMESTAMP);");
            $RESULT['is_success'] = $sth->execute(array(':user_id' => $userId, ':article_id' => $artId, ':comment_text' => $text));
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
    }

    public function deleteComment($commentId) {
        if (!FilterData::isCorrect($commentId, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID);
        }

        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("DELETE FROM mvc_comments WHERE comment_id = :id;");
            $RESULT['is_success'] = $sth->execute(array(':id' => $commentId));
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
    }

    public static function getCategories($catId = null){
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);
            $cat = null;
            if ($catId != null) {
                if (!FilterData::isCorrect($catId, FilterData::CHECK_ID)){
                    throw new MVCException(E_WRONG_ID);
                }
                $sth = $dbh->prepare("SELECT * FROM mvc_categories WHERE cat_id = :catId");
                $sth->execute(array(':catId' => $catId));
                $cat = $sth->fetch();
            }
            else {
                $sth = $dbh->prepare("SELECT * FROM mvc_categories");
                $sth->execute();
                $cat = $sth->fetchAll();
            }
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        return $cat;
    }

    public function getArticleById($articleId){
        if (!FilterData::isCorrect($articleId, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID.': '.$articleId);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT * FROM mvc_articles WHERE article_id = :art_id");
            $sth->execute(array(':art_id' => $articleId));
            $this->RESULT['article'] = $sth->fetch();

            $cat = $this->getCategories($this->RESULT['article']['cat_id']);
            $this->RESULT['cat_name'] = $cat['cat_name'];

            $sth = $dbh->prepare("SELECT login, user_id FROM mvc_users WHERE user_id IN (SELECT user_id FROM mvc_comments WHERE article_id = :art_id )");
            $sth->execute(array(':art_id' => $articleId));
            while($user = $sth->fetch())
            {
                $this->RESULT['users'][$user['user_id']] = $user['login'];
            }

            $sth = $dbh->prepare("SELECT * FROM mvc_comments WHERE article_id = :art_id ORDER BY comment_date");
            $sth->execute(array(':art_id' => $articleId));

            $this->RESULT['comments'] = $sth->fetchAll();
            for($i = 0; $i < count($this->RESULT['comments']); $i++)
            {
                $this->RESULT['comments'][$i]['user_name'] = $this->RESULT['users'][$this->RESULT['comments'][$i]['user_id']];
            }

            unset($this->RESULT['users']);

            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
    }

    public function getArticlesByCat($catId){   // только заголовки статей по категории
        if (!FilterData::isCorrect($catId, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID.': '.$catId);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT article_id, str_article_id, article_title FROM mvc_articles WHERE cat_id = :cat_id");
            $sth->execute(array(':cat_id' => $catId));

            while($article = $sth->fetch()) {
                $id  = $article['article_id'];
                $this->RESULT[$id]  = array('article_title' => $article['article_title'], 'str_article_id' => $article['str_article_id']);
            }

            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
    }
// CHECK THIS!!
    public function getArticles($item_count = 10, $offset = 0, $sort = 'DESC'){
        if (!in_array($sort, FilterData::$sortOrdersWhiteList) ||
            filter_var($offset, FILTER_VALIDATE_INT) != $offset ||
            filter_var($item_count, FILTER_VALIDATE_INT) != $item_count){
            throw new MVCException(E_INCORRECT_DATA);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_EMULATE_PREPARES => false);
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);

            $num = $this->getArticlesNumber();
            if ($item_count > $num || $item_count < 1) $item_count = $num;
            unset($num);
            $sth = $dbh->prepare("SELECT * FROM mvc_articles ORDER BY (date_format(article_date, '%Y-%m-%d %H:%i:%s')) ".$sort." LIMIT :offset, :row_num;");
            $sth->bindParam(':row_num', $item_count);
            $sth->bindParam(':offset', $offset);
            $sth->execute();
            $articles = $sth->fetchAll();

            $ids = array();
            foreach($articles as $article){
                if (!in_array($article['user_id'], $ids, true)){
                    array_push($ids, $article['user_id']);
                }
            }
            foreach($ids as $id){
                $sth = $dbh->prepare("SELECT login FROM mvc_users WHERE user_id = :id;");
                $sth->execute(array(':id' => $id));
                $login = $sth->fetch();
                foreach($articles as &$article){
                    if ($article['user_id'] == $id){
                        $article['user_login'] = $login['login'];
                    }
                }
            }
            $this->RESULT['articles'] = $articles;
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
    }
    public function getArticlesHeaders($item_count = 100, $offset = 0, $sort = 'ASC') {
        if (!in_array($sort, FilterData::$sortOrdersWhiteList) ||
            filter_var($offset, FILTER_VALIDATE_INT) != $offset ||
            filter_var($item_count, FILTER_VALIDATE_INT) != $item_count){
            throw new MVCException(E_INCORRECT_DATA);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_EMULATE_PREPARES => false);
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);

            $num = $this->getArticlesNumber();

            if ($item_count > $num || $item_count < 1) $item_count = $num;
            unset($num);
            $sth = $dbh->prepare("SELECT article_id, str_article_id, article_title, cat_id FROM mvc_articles ORDER BY (date_format(article_date, '%Y-%m-%d %H:%i:%s')) ".$sort." LIMIT :offset, :num;");
            $sth->bindParam(':offset', $offset);
            $sth->bindParam(':num', $item_count);

            $sth->execute();
            $this->RESULT['articles_menu'] = $sth->fetchAll();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
    }

    public function deleteArticle($id){
        if (!FilterData::isCorrect($id, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID.': '.$id);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);

            $sth = $dbh->prepare("DELETE FROM mvc_articles WHERE article_id = :art_id");
            $this->RESULT['is_success'] = $sth->execute(array(':art_id' => $id));
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
    }

    public function updateArticle($article_id, $new_title, $new_text, $new_cat_id, $new_date, $new_str_id, $tags, $desc){
        if (empty($article_id) || empty($new_title) || empty($new_text) || empty($new_cat_id)) throw new MVCException(E_EMPTY_FIELD);

        $this->checkArticleData($new_cat_id, $new_date, $new_str_id, $new_text);
        if (!FilterData::isCorrect($article_id, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID.': '.$article_id);
        }
        if (!FilterData::isPermitTags($new_title, $tag) ||
            !FilterData::isPermitTags($desc, $tag) ||
            !FilterData::isPermitTags($tags, $tag)){
            throw new MVCException(E_DENY_HTML_TAGS.': '.$tag);
        }

        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);

            $sth = $dbh->prepare("UPDATE mvc_articles SET article_title = :title, article_text = :text, cat_id = :cat, article_date = :new_date, str_article_id = :str_id, tags = :tags, description = :description WHERE article_id = :id;");
            $this->RESULT['is_success'] = $sth->execute(array(':id' => $article_id, ':title' => $new_title, ':text' => $new_text, ':cat' => $new_cat_id, ':new_date' => $new_date, ':str_id' => $new_str_id, ':tags' => $tags, ':description' => $desc));

            $dbh = null;
        }catch (PDOException $e) {
            throw $e;
        }
    }

    public function getData(){
        return $this->RESULT;
    }

    public function getArticlesNumber(){
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=".HOST.";dbname=".DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT COUNT(*) FROM mvc_articles");
            $sth->execute();
            $number = $sth->fetch();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        return $number['COUNT(*)'];
    }

    public function insertArticle($title, $text, $cat_id, $date, $user_id, $str_id, $tags, $desc){
        if (empty($title) || empty($text) || empty($cat_id) || empty($date) || empty($user_id)) throw new MVCException(E_EMPTY_FIELD);
        $this->checkArticleData($cat_id, $date, $str_id, $text);

        if (!FilterData::isPermitTags($title, $tag) ||
            !FilterData::isPermitTags($desc, $tag) ||
            !FilterData::isPermitTags($tags, $tag)){
            throw new MVCException(E_DENY_HTML_TAGS.': '.$tag);
        }

        if (!FilterData::isCorrect($user_id, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID.': '.$user_id);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("INSERT INTO mvc_articles (user_id, cat_id, article_title, article_text, article_date, str_article_id, tags, description) VALUES (:user_id, :cat_id, :title, :text, :article_date, :str_id, :tags, :description)");
            $this->RESULT['is_success'] = $sth->execute(array(':user_id' => $user_id, ':cat_id' => $cat_id, ':title' => $title, ':text' => $text, ':article_date' => $date, ':str_id' => $str_id, ':tags' => $tags, ':description' => $desc));
            $dbh = null;
        }catch (PDOException $e) {
            throw $e;
        }
    }

    public static function getArticleIntId($articleStrId) {
        if (!FilterData::isCorrect($articleStrId, FilterData::CHECK_STR_ID)){
            throw new MVCException(E_WRONG_ID.': '.$articleStrId);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT article_id FROM mvc_articles WHERE str_article_id = :id");
            $sth->execute(array(':id' => $articleStrId));
            $strId = $sth->fetch();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        return $strId['article_id'];
    }

    public static function getCatIntId($catStrId){
        if (!FilterData::isCorrect($catStrId, FilterData::CHECK_STR_ID)){
            throw new MVCException(E_WRONG_ID.': '.$catStrId);
        }
        try {
            $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATA_BASE, LOGIN, PASS, $opt);
            $sth = $dbh->prepare("SELECT cat_id FROM mvc_categories WHERE str_cat_id = :id");
            $sth->execute(array(':id' => $catStrId));
            $strId = $sth->fetch();
            $dbh = null;
        }
        catch(PDOException $e) {
            throw $e;
        }
        return $strId['cat_id'];
    }
//$title, $text, $cat_id, $date, $user_id, $str_id, $tags, $desc
// $new_title, $new_text, $new_cat_id, $new_date, $new_str_id, $tags, $desc
    private function checkArticleData($cat_id, $date, $str_id, $text){
        if (!FilterData::isCorrect($cat_id, FilterData::CHECK_ID)){
            throw new MVCException(E_WRONG_ID.': '.$cat_id);
        }
        if (!FilterData::isCorrect($date, FilterData::CHECK_DATE)){
            throw new MVCException(E_WRONG_DATE);
        }
        if (!FilterData::isCorrect($str_id, FilterData::CHECK_STR_ID)){
            throw new MVCException(E_WRONG_STR_ID.': '.$str_id);
        }
        if (FilterData::isPermitTags($text, $tag) === 0){
            throw new MVCException(E_DENY_HTML_TAGS.': '.$tag);
        }
        /*
        if (!preg_match("/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01]) [0-2][0-9](\:[0-5][0-9]){2}$/", $date)){
            throw new MVCException(E_WRONG_DATE);
        }
        if (!preg_match('/^[a-z0-9]+([_|-]?[a-z0-9]+)*$/i', $str_id)){
            throw new MVCException(E_WRONG_STR_ID.': '.$str_id);
        }*/
    }
}