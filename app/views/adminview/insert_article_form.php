<form method="post" action="/admin/insert_article" enctype="multipart/form-data">
    <table id = "edit_article">
        <tr><td colspan="2"><h3> <? echo L_ADD_ARTICLE; ?> </h3></td></tr>
        <tr><td colspan="2"><? echo L_ARTICLE_STRING_ID; ?></td></tr>
        <tr><td colspan="2"> <input type="text" name="str_article_id" value=""></td></tr>
        <tr><td colspan="2"><? echo L_ARTICLE_TITLE; ?></td></tr>
        <tr><td colspan="2"> <input type="text" name="article_title" value=""></td></tr>
        <tr><td colspan="2"><? echo L_DESCRIPTION; ?></td></tr>
        <tr><td colspan="2" class="desc"><textarea maxlength="400" type="text" name="article_desc"></textarea></td></tr>
        <tr><td colspan="2"><? echo L_ARTICLE_TEXT; ?></td></tr>
        <tr><td colspan="2"> <textarea id="editor" name="article_text" value=""></textarea></td></tr>
        <tr><td><? echo L_ARTICLE_CAT; ?></td><td><? echo L_DATE; ?></td></tr>
        <tr><td><select name="article_cat">
                    <?
                    if (!empty($data['categories'])) {
                        $t = new Template('app/views/adminview/', 'cb_cat_menu.htx');
                        try {
                            $catMenuBody = '';
                            foreach($data['categories'] as $cat) {
                                $t->addKey('is_sel', '');
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
        <td><input type="datetime"  name="article_date" value="<? echo date("Y-m-d h:m:s"); ?>"></td></tr>
        <tr><td colspan="2"><? echo L_TAGS; ?></td></tr>
        <tr><td colspan="2"><input type="text" maxlength="120" name="article_tags" value=""></td></tr>
        <tr><td colspan="2"><input type="submit" value="<? echo L_PUBLISH; ?>"></td></tr>
    </table>
    <input type="hidden" name="user_id" value="<? echo $_SESSION['user_id']; ?>">
</form>