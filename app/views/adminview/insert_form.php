<div id="user_table">
    <h3> <? echo L_ADD_USER; ?> </h3>
    <form method="post" action="/admin/insert_user">
    <table>
        <tr><td class="rightcol"><? echo L_USER_LOGIN; ?></td><td> <input type="text" name="login" value=""> </td></tr>
        <tr><td class="rightcol"><? echo L_USER_PASS; ?></td><td> <input type="text" name="pass" value=""> </td></tr>
        <tr><td class="rightcol"><? echo L_USER_MAIL; ?></td><td> <input type="text" name="email" value=""> </td></tr>
        <tr><td class="rightcol"><? echo L_USER_STATUS; ?></td><td> <input type="text" name="status" value="0"> </td></tr>
        <tr><td class="rightcol"><? echo L_USER_IS_ACTIVE; ?></td><td> <input type="checkbox" name="is_active" checked> </td></tr>
        <tr><td colspan="2" class="center_button"><input type="submit" value="<? echo L_SUBMIT; ?>"></td></tr>
    </table>
    </form>
</div>