<div  id="user_table">
<h1><?echo L_LOGIN_PAGE?></h1>
<form action='/users/sign_in' method='post'>
    <table>
    <tr><td class="rightcol"><?echo L_USER_LOGIN; ?>:</td> <td><input type='text' name='login' size="32"></td></tr>
    <tr><td class="rightcol"><?echo L_USER_PASS; ?>:</td> <td><input type='password' name='password' size="32"></td></tr>
    <tr><td class="center_button" colspan="2">
            <? if ($_SESSION['critical_attempts'] > MAX_ATTEMPTS_NUMBER){
                echo "<div class=\"g-recaptcha\" data-sitekey=\"".RECAPTCHA_PUBLIC_KEY."\"</div>";
            } ?>
    </td></tr>
    <tr><td class="center_button" colspan="2"><input type='submit' value="<? echo L_SUBMIT; ?>"></td></tr>
    </table>
    <? if ($_SESSION['critical_attempts'] > MAX_ATTEMPTS_NUMBER){
        echo "<input type='hidden' name='checkReCaptcha' value='1'>";
    } ?>
        <input name="try_login" type="hidden" value="1">
</form><br>
    <a href="/users/load_restore_pass"><?echo E_USER_FORGOT_PASS; ?></a>
</div>
