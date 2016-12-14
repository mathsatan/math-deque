<form name="form_update_article" method="post" action="/admin/update_article/article_id/<? if (LINKS_TYPE === 1) echo $data['article']['str_article_id']; else echo $data['article']['article_id']; ?>" enctype="multipart/form-data">
    <table id = "edit_article">
        <tr><td colspan="2"><h3> <? echo L_ARTICLE_UPDATE; ?> </h3></td></tr>
        <tr><td colspan="2"><? echo L_ARTICLE_STRING_ID; ?></td></tr>
        <tr><td colspan="2"> <input type="text" name="new_str_id" value="<? echo $data['article']['str_article_id']; ?>"></td></tr>
        <tr><td colspan="2"><? echo L_ARTICLE_TITLE; ?></td></tr>
        <tr><td colspan="2"> <input type="text" name="new_title" value="<? echo $data['article']['article_title']; ?>"></td></tr>
        <tr><td colspan="2"><? echo L_DESCRIPTION; ?></td></tr>
        <tr><td colspan="2" class="desc"><textarea maxlength="400" name="new_desc"><? echo $data['article']['description']; ?></textarea></td></tr>
        <tr><td colspan="2"><? echo L_ARTICLE_TEXT; ?></td></tr>
        <tr><td colspan="2"> <textarea  id="editor" name="new_text"><? echo $data['article']['article_text']; ?></textarea></td></tr>
        <tr><td><? echo L_ARTICLE_CAT; ?></td><td><? echo L_DATE; ?></td></tr>
        <tr><td><select name="new_cat">
                    <?
                    if (!empty($data['categories'])) {
                        $t = new Template('app/views/adminview/', 'cb_cat_menu.htx');
                        try {
                            $catMenuBody = '';
                            foreach($data['categories'] as $cat) {
                                ($data['article']['cat_id'] === $cat['cat_id']) ? $t->addKey('is_sel', 'selected') : $t->addKey('is_sel', '');
                                $t->addKey('cat_id', $cat['cat_id']);
                                $t->addKey('cat_name', $cat['cat_name']);
                                $catMenuBody .= $t->parseTemplate();
                            }
                        }catch (TemplateException $e){
                            ob_end_clean();
                            throw $e;
                        }
                        unset($t);
                        echo $catMenuBody;
                    }
                    ?>
                </select></td>
            <td> <input type="datetime"  name="article_date" value="<? echo $data['article']['article_date']; ?>"></td></tr>
        <tr><td> <? echo L_ARTICLE_AUTHOR; ?>: <? echo $data['author']['login']; ?> </td>
        <tr><td colspan="2"><? echo L_TAGS; ?></td></tr>
        <tr><td colspan="2"><input type="text" maxlength="120" name="new_tags" value="<? echo $data['article']['tags']; ?>"></td></tr>
        <tr><td colspan="2"><input type="submit" value="<? echo L_SUBMIT; ?>"></td></tr>
    </table>
</form>