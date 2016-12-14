<div id="user_table">
    <h3><? echo L_PIC_UPDATE; ?></h3>
    <img src="<? echo $data['pic_info']['pic_path']; ?>">
    <form method="post" action="/admin/update_pic/pic_id/<? echo $data['pic_info']['pic_id']; ?>">
    <table>
        <tr><td class="rightcol"><? echo L_PIC_ALIGN; ?></td>
            <td><select name="new_align">
                    <option <? if ($data['pic_info']['pic_align'] == 'left') echo 'selected'; ?> value="left">left</option>
                    <option <? if ($data['pic_info']['pic_align'] == 'right') echo 'selected'; ?> value="right">right</option>
                </select></td></tr>
        <tr><td class="rightcol"><? echo L_PIC_ALT; ?></td>
            <td><input type="text" name="new_alt" value="<? echo $data['pic_info']['pic_alt']; ?>"></td></tr>
        <tr><td class="rightcol"><? echo L_PIC_DESC; ?></td>
            <td><input type="text" name="new_desc" value="<? echo $data['pic_info']['cap']; ?>"></td></tr>
        <tr><td colspan="2" class="center_button"><input type="submit" value="<? echo L_SUBMIT; ?>"></td></tr>
    </table>
    </form>
</div>