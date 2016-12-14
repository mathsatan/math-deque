<? include 'txt/welcome_description.php'; ?>

<h3><? echo L_NEWS; ?></h3>
<?
if (!empty($data['articles']))
{
    $t = new Template('app/views/mainview/', 'article.htx');
    try {
        $list = '';
        foreach($data['articles'] as $article)
        {
            $t->addKey('article_title', $article['article_title']);
            $t->addKey('date', $article['article_date']);
            $t->addKey('path_to_title_pic', $article['title_pic']);
            (LINKS_TYPE === 1) ? $id = $article['str_article_id'] : $id = $article['article_id'];
            $t->addKey('article_id', $id);
            /*$begin = mb_substr(preg_replace('/\${2}(.*)\${2}/', "", $article['description']), 0, 300, 'UTF-8');*/
            $t->addKey('article_text_briefly', $article['description']);
            $list .= $t->parseTemplate();
        }

        echo $list;
    }catch (TemplateException $e){
        ob_end_clean();
        throw $e;
    }
    unset($t);
}
?>
