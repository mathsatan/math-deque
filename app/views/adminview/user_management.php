<?
if (empty($data['users'])) {
    ob_end_clean();
    throw new MVCException(E_USER_NOT_FOUND);
}
?>
<div id="user_table">
    <h3> <? echo L_USER_LIST; ?> </h3>
    <table>
        <tr><td><? echo L_USER_ID; ?></td><td><? echo L_USER_LOGIN; ?></td><td><? echo L_USER_MAIL; ?></td><td><? echo L_USER_STATUS; ?></td><td><? echo L_USER_IS_ACTIVE; ?></td>
            <td><img src="/img/change.png"/></td>
            <td><img src="/img/delete.png"/></td></tr>
        <?
        $t = new Template('app/views/adminview/', 'user_table_item.htx');
        try {
            $userList = '';
            foreach($data['users'] as $user) {
                $t->addKey('user_id', $user['user_id']);
                $t->addKey('user_login', $user['login']);
                $t->addKey('user_email', $user['email']);
                $t->addKey('user_status', $user['status']);
                $t->addKey('user_is_active', $user['is_active']);

                $t->addKey('hint_update', L_USER_UPDATE);
                $t->addKey('hint_delete', L_USER_DELETE);

                $userList .= $t->parseTemplate();
            }
            echo $userList;
        }catch (TemplateException $e){
            ob_end_clean();
            throw $e;
        }
        unset($t);
        ?>
    </table>
</div>
<div class="pages">
    <?
    try{
        $link = new Template('app/views/adminview/', 'page_item.htx');
        $pages = '';
        for($i = 0; $i < ceil($data['users_count']/USERS_COUNT); $i++){
            $link->addKey('page_num', $i);
            $link->addKey('page', $i + 1);
            $link->addKey('action', 'index');
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