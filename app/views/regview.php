<div id="user_table">
<h1><?echo L_REG_PAGE?></h1>
<form action='' method='post'>
    <table>
        <tr><td class="rightcol"><?echo L_USER_LOGIN?>*</td> <td><input type='text' name='login' size="32"></td></tr>
        <tr><td class="rightcol"><?echo L_USER_PASS?>*</td><td><input type='password' name='password' size="32"></td></tr>
        <tr><td class="rightcol"><?echo L_USER_PASS_REPEAT?>*</td><td><input type='password' name='password2' size="32"></td></tr>
        <tr><td class="rightcol"><?echo L_USER_MAIL?>*</td> <td><input type='text' name='email' size="32"></td></tr>
        <tr><td class="center_button" colspan="2"><div class="g-recaptcha" data-sitekey="<? echo RECAPTCHA_PUBLIC_KEY?>"</div></td></tr>
        <tr><td colspan="2" class="center_button"><input type='submit' value="<? echo L_SUBMIT; ?>"</td></tr>
    </table>
    <input type="hidden" name="try_reg" value="1">
</form>
</div>