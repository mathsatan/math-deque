<?
if (empty($data)) {
    ob_end_clean();
    throw new MVCException(E_NO_ARTICLE_DATA);
}
?>
<div id="articles_list">
    <h1><? echo $data['current_cat']['cat_name']; ?></h1>
    <? if (file_exists($_SERVER['DOCUMENT_ROOT'].'/img/cat/'.$data['current_cat']['str_cat_id'].'.png')){
        echo "<img class='cat_pic' src='/img/cat/".$data['current_cat']['str_cat_id'].".png' alt='".$data['current_cat']['cat_name']."' title='".$data['current_cat']['cat_name']."'>";
    }
    $x = false;
    foreach($data['articles_menu'] as $item) {
        if ($data['current_cat']['cat_id'] == $item['cat_id']) {
            $x = true;
            break;
        }
    }
    if (empty($data['articles_menu']) || (!$x)) {
        echo '<h3>' . L_NO_ARTICLES_FOUND . '</h3>';
    }else{
        $t = new Template('app/views/mainview/', 'article_item.htx');
        try {
            $items = '';
            foreach($data['articles_menu'] as $articleInfo) {
                if($articleInfo['cat_id'] == $data['current_cat']['cat_id'] ) {
                    (LINKS_TYPE === 1) ? $currId = $articleInfo['str_article_id'] : $currId = $articleInfo['article_id'];
                    $t->addKey('article_id', $currId);
                    $t->addKey('article_title', $articleInfo['article_title']);
                    $items .= $t->parseTemplate();
                }
            }
            echo '<ul>'.$items.'</ul>';
        } catch(TemplateException $e) {
            ob_end_clean();
            throw $e;
        }
        unset($t);
    }
    ?>
</div>