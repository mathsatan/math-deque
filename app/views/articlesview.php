<?
if (empty($data['article_data']['article'])) {
    ob_end_clean();
    throw new MVCException(E_NO_ARTICLE_DATA);
}
(empty($data['article_data']['comments']))? $str = L_NO_COMMENTS : $str = L_COMMENTS;
?>
<div itemscope itemtype="http://schema.org/Article" class="article">
    <h1 itemprop="name"><? echo $data['article_data']['article']['article_title']; ?></h1>
	<div itemprop="articleBody">
    <? echo $data['article_data']['article']['article_text']; ?>
	</div>
    <div class="donate" ><a href="/main/about"><? echo L_DONATE; ?></a></div>
    <script type="text/javascript" src="/js/share42.js"></script>
    <div class="share42init" data-path="/img/logos/"></div>
    <h3><? echo $str ?></h3>
    <?
    if (!empty($data['article_data']['comments']))
    {
        $t = new Template('app/views/mainview/', 'comment.htx');
        $comments = '';
        try {
            for ($i = 0; $i < count($data['article_data']['comments']); $i++) {
                $t->addKey('user_name', $data['article_data']['comments'][$i]['user_name']);
                $t->addKey('comment_date', $data['article_data']['comments'][$i]['comment_date']);
                $t->addKey('article_comment_text', $data['article_data']['comments'][$i]['comment_text']);

                if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] === $data['article_data']['comments'][$i]['user_id']){
                    $button = new Template('app/views/mainview/', 'delete_comment.htx');
                    $button->addKey('comment_id', $data['article_data']['comments'][$i]['comment_id']);
                    (LINKS_TYPE === 1) ? $currId = $data['article_data']['article']['str_article_id'] : $currId = $data['article_data']['article']['article_id'];
                    $button->addKey('article_id', $currId);
                    $button->addKey('str_article_id', $data['article_data']['article']['str_article_id']);
                    $t->addKey('del_button', $button->parseTemplate());
                }else{
                    $t->addKey('del_button', '');
                }
                $comments .= $t->parseTemplate();
            }
            echo $comments;
        } catch(TemplateException $e) {
            ob_end_clean();
            throw $e;
        }
        unset($t);
    }
    if (!empty($_SESSION['user_id']))
    {
        $t = new Template('app/views/mainview/', 'add_comment.htx');
        try{
            $t->addKey('article_id', $data['article_data']['article']['article_id']);
            $t->addKey('str_article_id', $data['article_data']['article']['str_article_id']);
            $t->addKey('user_id', $_SESSION['user_id']);


            $t->addKey('your_comment', L_YOUR_COMMENT);
            $t->addKey('submit', L_SUBMIT);

            $t->display();
        }catch (TemplateException $e) {
            throw $e;
        }
        unset($t);
    }
    ?>