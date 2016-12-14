<div id="user_table">
    <?
    if (empty($data['pics'])) {
        echo '<h3>'.E_PICS_NOT_FOUND.'</h3>';
    }else
        echo '<h3>'.L_PIC_LIST.'</h3>';
    ?>
    <table>
        <tr><td><? echo L_PIC_VIEW; ?></td><td><? echo L_PIC_CODE; ?></td><td><img src="/img/change.png"/></td><td><img src="/img/delete.png"/></td></tr>
        <?
        $t = new Template('app/views/adminview/', 'pic_item.htx');
        try {
            $list = '';
            foreach($data['pics'] as $pic) {
                $t->addKey('pic_path', $pic['pic_path']);
                $t->addKey('pic_path_small', preg_replace('/\/original\//', '/small/', $pic['pic_path']));
                $t->addKey('pic_alt', $pic['pic_alt']);
                $t->addKey('pic_align', $pic['pic_align']);
                $t->addKey('pic_id', $pic['pic_id']);
                $t->addKey('pic_cap', $pic['cap']);

                $t->addKey('hint_update', L_PIC_UPDATE);
                $t->addKey('hint_delete', L_PIC_DELETE);
                $t->addKey('pic_open_hint', L_PIC_HINT_OPEN);

                $list .= $t->parseTemplate();
            }echo $list;
            unset($t);
        }catch (TemplateException $e){
            ob_end_clean();
            throw $e;
        }
        ?>
    </table>
</div>
<div class="pages">
    <?
    try{
        $link = new Template('app/views/adminview/', 'page_item.htx');
        $pages = '';
        for($i = 0; $i < ceil($data['pics_count']/IMG_COUNT); $i++){
            $link->addKey('page_num', $i);
            $link->addKey('page', $i + 1);
            $link->addKey('action', 'media_list');
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
