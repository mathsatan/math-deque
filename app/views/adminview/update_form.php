<div id="user_table">
    <h3> <? echo L_USER_UPDATE; ?> </h3>
    <form method="post" action="/admin/update_user/user_id/<? echo $data['user_info']['user_id']; ?>">
    <table>
        <tr><td class="rightcol"><? echo L_USER_ID; ?></td><td> <? echo $data['user_info']['user_id']; ?> </td></tr>
        <tr><td class="rightcol"><? echo L_USER_LOGIN; ?></td><td> <input type="text" name="new_login" value="<? echo $data['user_info']['login']; ?>"> </td></tr>
        <tr><td class="rightcol"><? echo L_USER_PASS; ?></td><td> <input type="text" name="new_pass" value=""> </td></tr>
        <tr><td class="rightcol"><? echo L_USER_MAIL; ?></td><td> <input type="text" name="new_email" value="<? echo $data['user_info']['email']; ?>"> </td></tr>
        <tr><td class="rightcol"><? echo L_USER_STATUS; ?></td><td> <input type="text" name="new_status" value="<? echo $data['user_info']['status']; ?>"> </td></tr>
        <tr><td class="rightcol"><? echo L_USER_IS_ACTIVE; ?></td><td> <input type="checkbox" name="new_is_active" <? if ($data['user_info']['is_active'] === "1") echo 'checked'; ?>> </td></tr>
        <tr><td colspan="2" class="center_button"><input type="submit" value="<? echo L_SUBMIT; ?>"></td></tr>
    </table>
    </form>
</div>