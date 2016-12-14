<div  id="user_table">
    <h1><?echo L_USER_RESTORE_PASS; ?></h1>
    <form action='/users/restore_pass' method='post'>
        <table>
            <tr><td class="rightcol"><?echo L_USER_MAIL; ?>:</td> <td><input type='text' name='restore_mail'></td></tr>
            <tr><td colspan="2"><div class="g-recaptcha" data-sitekey="<? echo RECAPTCHA_PUBLIC_KEY?>"</div></td></tr>
            <tr><td class="center_button" colspan="2"><input type='submit' size="100" value="<? echo L_SUBMIT; ?>"></td></tr>
        </table>
    </form><br>
    <? include 'txt/restore_info.htx'; ?>
</div>
