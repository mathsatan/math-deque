<div id="images">
    <h3> <? echo L_PIC_ADD; ?> </h3>
    <form method="post" action="/admin/insert_pic" enctype="multipart/form-data">
    <table>
        <tr><td class="rightcol"><? echo L_URL_PIC; ?> <input name="r1" value="url" type="radio" onclick="doEnableElem(this)"></td>
            <td><input id="pic_url" type="text" name="article_image_url" value="" disabled></td></tr>
        <tr><td class="rightcol"><? echo L_LOAD_PIC; ?> <input name="r1" value="load" type="radio" checked onclick="doEnableElem(this)"></td>
            <td><input id="pic_attach" type="file" accept="image/jpeg,image/png,image/gif" name="article_image"></td>
        </tr>
        <tr><td class="rightcol"><? echo L_PIC_ALIGN; ?></td>
            <td><select name="align_type">
                    <option selected value="left">left</option>
                    <option value="right">right</option>
                </select></td>
        </tr>
        <tr><td class="rightcol"><? echo L_PIC_ALT; ?></td>
            <td><input type="text" name="tag_alt" value=""></td>
        </tr>
        <tr><td class="rightcol"><? echo L_PIC_DESC; ?></td>
            <td><input type="text" name="pic_desc" value=""></td>
        </tr>
        <tr><td colspan="2" class="center_button"><input type="submit" value="<? echo L_SUBMIT; ?>"></td></tr>
    </table>
    </form>
</div>