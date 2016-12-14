<div class="side_menu_item">
    <h3><? echo L_ARTICLES_LIST; ?></h3>
    <ul>
        <?
        if (!empty($data['articles_menu'])) {
            $menu = new Template('app/views/side_menus/', 'article_menu.htx');
            $item = new Template('app/views/side_menus/', 'article_menu_item.htx');
            try {
                foreach ($data['categories'] as $cat) {
                    $f = false;
                    foreach ($data['articles_menu'] as $articleItem) {
                        if ($articleItem['cat_id'] == $cat['cat_id']) {
                            $f = true;
                            (LINKS_TYPE === 1) ? $articleId = $articleItem['str_article_id'] : $articleId = $articleItem['article_id'];
                            $item->addKey('article_id', $articleId);
                            $item->addKey('article_name', $articleItem['article_title']);
                            $menu->addKey('menu_body', $item->parseTemplate(), true);
                            $item->clearKeys();
                        }
                    }
                    if ($f){
                        $menu->addKey('cat_name', $cat['cat_name']);
                        echo $menu->parseTemplate();
                        $menu->clearKeys();
                    }
                }
            } catch (TemplateException $e) {
                /*ob_end_clean();
                throw $e;*/
                echo $e->getMessage();
            }
            unset($menu);
            unset($item);
        }else{
            echo E_ARTICLES_NOT_FOUND;
        }
        ?>
    </ul>
</div>


