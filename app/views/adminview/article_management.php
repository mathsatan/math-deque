<?
if (empty($data['articles'])) {
    ob_end_clean();
    throw new MVCException(E_ARTICLES_NOT_FOUND);
}
?>
<div id="user_table">
    <h3> <? echo L_ARTICLES_LIST; ?> </h3>
    <!--<a href="/admin/load_insert_article_form"><?/* echo L_ADD_ARTICLE; */?></a>-->
    <table>
        <tr><td><? echo L_ARTICLE_ID; ?></td><td><? echo L_ARTICLE_TITLE; ?></td><td><? echo L_ARTICLE_AUTHOR; ?></td>
            <td><img src="/img/change.png"/></td>
            <td><img src="/img/delete.png"/></td></tr>
        <?
        $t = new Template('app/views/adminview/', 'article_table_item.htx');
        try {
            $list = '';
            foreach($data['articles'] as $article) {
                (LINKS_TYPE === 1) ? $articleId = $article['str_article_id'] : $articleId = $article['article_id'];
                $t->addKey('article_id', $articleId);
                $t->addKey('article_title', $article['article_title']);
                $t->addKey('article_author', $article['user_login']);

                $t->addKey('hint_update', L_ARTICLE_UPDATE);
                $t->addKey('hint_delete', L_ARTICLE_DELETE);

                $list .= $t->parseTemplate();
            }
            echo $list;
        }catch (TemplateException $e){
            ob_end_clean();
            throw $e;
        }
        unset($t);
        ?>
    </table>
</div>
<div class="pages">
    <?
    try{
        $link = new Template('app/views/adminview/', 'page_item.htx');
        $pages = '';
        for($i = 0; $i < ceil($data['articles_count']/ARTICLES_COUNT); $i++){
            $link->addKey('page_num', $i);
            $link->addKey('page', $i + 1);
            $link->addKey('action', 'articles_list');
            $pages .= $link->parseTemplate();
            $link->clearKeys();
        }echo $pages;
        unset($link);
    }catch (TemplateException $e){
        ob_end_clean();
        throw $e;
    }
    ?>
</div>