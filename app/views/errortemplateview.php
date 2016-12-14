<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><? echo L_TITLE; ?></title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <!--<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono&subset=latin,cyrillic' rel='stylesheet' type='text/css'>-->

    <link rel="shortcut icon" href="/img/icon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="/css/top_menu.css" />
    <link rel="stylesheet" type="text/css" href="/css/error_style.css" />
    <script src="/js/jquery-2.1.1.js" type="text/javascript"></script>
    <script src="/js/top_menu.js" type="text/javascript"></script>
</head>
<body>
<div id="wrapper">
    <div id="header"></div>
    <div class='nav'>
        <ul>
            <li><a href='/'><? echo L_MAIN; ?></a></li>
            <!--<li><a href='/main/inform_admin'><?/* echo L_REPORT_ADMIN; */?></a></li>-->
            <li class='lamp'><span></span></li>
        </ul>
        <div class="login_menu">
            <h4>
                <?php
                if ($_SESSION['user_id'] == true)
                    echo L_HELLO.', '.$_SESSION['login']." <a href='/users/sign_out'> ".L_OUT.'</a>';
                else
                    echo '<a href="/users/sign_in">'.L_LOGIN.'</a>&nbsp|&nbsp<td><a href="/users/sign_up">'.L_REG.'</a>';
                ?>
            </h4>
        </div>
    </div>
    <div id="page">
        <?php include 'app/views/'.$content_view; ?>
        <br class="clearfix" />
    </div>
    <!-----PAGE BOTTOM------>
    <div id="page-bottom">
        <div id = "lang_menu">
            <form method="post" id="lang_ru"><input type="hidden" name="lang" value="ru"></form>
            <form method="post" id="lang_en"><input type="hidden" name="lang" value="en"></form>
            <a href="#" onclick="document.getElementById('lang_ru').submit(); return false;">ru</a>&nbsp;|&nbsp;
            <a href="#" onclick="document.getElementById('lang_en').submit(); return false;">en</a>
        </div>
        <br class="clearfix" />
    </div>
</div>
<div id="footer">
    <h4><a href="/">math-deque.rhcloud.com</a> &copy; 2015</a></h4>
</div>

</body>
</html>