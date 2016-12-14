<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><? echo L_ADMIN_PANEL; ?></title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <!--<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono&subset=latin,cyrillic' rel='stylesheet' type='text/css'>-->

    <link rel="shortcut icon" href="/img/icon.ico" type="image/x-icon">

	<link rel="stylesheet" type="text/css" href="/css/admin_style.css" />
    <link rel="stylesheet" type="text/css" href="/css/top_menu.css" />
    <link rel="stylesheet" type="text/css" href="/css/message.css" />
	<script src="/js/jquery-2.1.1.js" type="text/javascript"></script>
    <script src="/js/top_menu.js" type="text/javascript"></script>
    <script src="/js/message.js" type="text/javascript"></script>
    <script src="/js/editor/tinymce.min.js"> </script>
    <script>tinymce.init({selector:'textarea#editor', plugins: "code", convert_urls: false});</script>

    <script src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <noindex>
    <script type="text/javascript">
        $(document).ready(function() {
            var msg = decodeURIComponent("<?echo $data['message']?>");
            var msg_type = '<?echo $data['msg_type']?>';
            if (msg != '')
                if (msg_type == 'classic')
                    $.stickr({note: msg, className: 'classic'});
                else
                    $.stickr({note: msg, className: 'classic stick_error', sticked: true});
        });
        function doEnableElem(radio){
            if (radio.value == 'load'){
                document.getElementById('pic_attach').disabled = false;
                document.getElementById('pic_url').disabled = true;
            }
            else{
                document.getElementById('pic_attach').disabled = true;
                document.getElementById('pic_url').disabled = false;
            }
        }
    </script>
    </noindex>
</head>
<body>

<div id="wrapper">
    <div class='nav'>
        <ul>
            <li><a href='/'><? echo L_MAIN; ?></a></li>
            <li><a href='/admin/index'><? echo L_USER_MANAGEMENT; ?></a>
                <ul>
                    <li><a href='/admin/index'><? echo L_USER_LIST; ?></a></li>
                    <li><a href='/admin/load_insert_form'><? echo L_ADD_USER; ?></a></li>
                </ul>
            </li>
            <li><a href='/admin/articles_list'><? echo L_ARTICLES_MANAGEMENT; ?></a>
                <ul>
                    <li><a href='/admin/articles_list'><? echo L_ARTICLES_LIST; ?></a></li>
                    <li><a href='/admin/load_insert_article_form'><? echo L_ADD_ARTICLE; ?></a></li>
                </ul>
            </li>
            <li><a href='/admin/media_list'><? echo L_PIC_VIEW; ?></a>
                <ul>
                    <li><a href='/admin/media_list'><? echo L_PIC_LIST; ?></a></li>
                    <li><a href='/admin/load_insert_pic'><? echo L_PIC_ADD; ?></a></li>
                </ul>
            </li>
            <li class='lamp'><span></span></li>
        </ul>
        <div class="login_menu">
                <?php
                if ($_SESSION['user_id'] == true)
                    echo L_HELLO.', '.$_SESSION['login']." <a href='/users/sign_out'> ".L_OUT.'</a>';
                else
                    echo '<a href="/users/sign_in">'.L_LOGIN.'</a>&nbsp|&nbsp<td><a href="/users/sign_up">'.L_REG.'</a>';
                ?>
        </div>
    </div>

    <div id="page">
        <?php include 'app/views/adminview/'.$content_view; ?>
        <br class="clearfix" />
    </div>
    <noindex>
    <div id="page-bottom">
        <div id = "lang_menu">
            <form method="post" id="lang_ru"><input type="hidden" name="lang" value="ru"></form>
            <form method="post" id="lang_en"><input type="hidden" name="lang" value="en"></form>
            <a href="#" onclick="document.getElementById('lang_ru').submit(); return false;">ru</a>&nbsp;|&nbsp;
            <a href="#" onclick="document.getElementById('lang_en').submit(); return false;">en</a>
        </div>
        <br class="clearfix" />
    </div>
    </noindex>
</div>
<div id="footer">
    <a href="/">math-deque.rhcloud.com</a> &copy; 2015</a>
</div>

</body>
</html>