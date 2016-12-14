<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="<? if (!empty($data['article_data']['article']['description']))echo strip_tags(preg_replace('/<img.*src=([^\>]*)>/ui', '', $data['article_data']['article']['description'])); ?>" />
	<meta name="keywords" content="<? if (!empty($data['article_data']['article']['tags']))echo $data['article_data']['article']['tags']; ?>" />
	<title><?  if (!empty($data['article_data']['article']['article_title']))echo $data['article_data']['article']['article_title']; else echo L_TITLE;?></title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>

    <link rel="shortcut icon" href="/img/icon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="/css/top_menu.css" />
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/css/message.css" />
	<script src="/js/jquery-2.1.1.js" type="text/javascript"></script>
    <script src="/js/message.js" type="text/javascript"></script>
    <script src="/js/top_menu.js" type="text/javascript"></script>
    <script src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <noindex>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-68170546-1', 'auto');
            ga('send', 'pageview');
        </script>

        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter32731985 = new Ya.Metrika({
                            id:32731985,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true
                        });
                    } catch(e) { }
                });
                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/32731985" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->

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
    </script>
    </noindex>
</head>
<body>
<div id="wrapper">
    <div id="header"></div>
    <div class='nav'>
        <ul>
            <li><a href='/'><? echo L_MAIN; ?></a></li>
            <li><a href='#' title="<? echo L_ARTICLES_MATH; ?>"><? echo L_ARTICLES; ?></a>
                <ul>
                    <?
                    if (!empty($data['categories'])) {
                        try {
                            $t = new Template('app/views/', 'cat_menu.htx');
                            $catMenuBody = '';
                            foreach($data['categories'] as $cat) {
                                (LINKS_TYPE === 1) ? $id = $cat['str_cat_id'] : $id = $cat['cat_id'];
                                $t->addKey('cat_id', $id);
                                $t->addKey('cat_name', $cat['cat_name']);
                                $catMenuBody .= $t->parseTemplate();
                            }echo $catMenuBody;
                            unset($t);
                        }catch (TemplateException $e){
                            ob_end_clean();
                            throw $e;
                        }
                    }
                    ?>
                </ul>
            </li>
            <li><a href='/articles/article_by_cat/cat_id/formula' title="<? echo L_FORMULA_DESC; ?>"><? echo L_FORMULA; ?></a></li>
            <li><a href='#' title="<? echo L_ORDER_WORK_MATH; ?>"><? echo L_ORDER; ?></a>
                <ul>
                    <li><a href='/main/order' title="<? echo L_ORDER_WORK_MATH; ?>"><? echo L_ORDER; ?></a></li>
                    <li><a href='/main/about'><? echo L_ABOUT; ?></a></li>
                </ul>
            </li>
            <li><a href='/calc' title="<? echo L_CALC_HINT; ?>"><? echo L_CALC; ?></a></li>
            <li class='lamp'><span></span></li>
        </ul>
        <noindex>
        <div class="login_menu">
                <?php
                if ($_SESSION['user_id'] == true)
                    echo L_HELLO.', '.$_SESSION['login']." <a href='/users/sign_out'> ".L_OUT.'</a>';
                else
                    echo '<a href="/users/sign_in">'.L_LOGIN.'</a>&nbsp|&nbsp<td><a href="/users/sign_up">'.L_REG.'</a>';
                ?>
        </div>
        </noindex>
    </div>
    <div id="page">
       <div id="side_menu">
           <?php include 'app/views/side_menus/side_menu_news.php';
           include 'app/views/side_menus/side_social.php';?>		  
        </div>
        <div id="content">
            <?php include 'app/views/'.$content_view; ?>
            <br class="clearfix" />
        </div>
        <br class="clearfix" />
    </div>

    <noindex>
    <div id="page-bottom">
        <!--bigmir)net TOP 100-->
        <script type="text/javascript" language="javascript"><!--
            function BM_Draw(oBM_STAT){
                document.write('<table cellpadding="0" cellspacing="0" border="0" style="display:inline;margin-right:4px;"><tr><td><div style="margin:0px;padding:0px;font-size:1px;width:88px;"><div style="background:url(\'//i.bigmir.net/cnt/samples/diagonal/b59_top.gif\') no-repeat bottom;"> </div><div style="font:10px Tahoma;background:url(\'//i.bigmir.net/cnt/samples/diagonal/b59_center.gif\');"><div style="text-align:center;"><a href="http://www.bigmir.net/" target="_blank" style="color:#0000ab;text-decoration:none;font:10px Tahoma;">bigmir<span style="color:#ff0000;">)</span>net</a></div><div style="margin-top:3px;padding: 0px 6px 0px 6px;color:#003596;"><div style="float:left;font:10px Tahoma;">'+oBM_STAT.hosts+'</div><div style="float:right;font:10px Tahoma;">'+oBM_STAT.hits+'</div></div><br clear="all"/></div><div style="background:url(\'//i.bigmir.net/cnt/samples/diagonal/b59_bottom.gif\') no-repeat top;"> </div></div></td></tr></table>');
            }
            //-->
        </script>
        <script type="text/javascript" language="javascript"><!--
            bmN=navigator,bmD=document,bmD.cookie='b=b',i=0,bs=[],bm={o:1,v:16942487,s:16942487,t:0,c:bmD.cookie?1:0,n:Math.round((Math.random()* 1000000)),w:0};
            for(var f=self;f!=f.parent;f=f.parent)bm.w++;
            try{if(bmN.plugins&&bmN.mimeTypes.length&&(x=bmN.plugins['Shockwave Flash']))bm.m=parseInt(x.description.replace(/([a-zA-Z]|\s)+/,''));
            else for(var f=3;f<20;f++)if(eval('new ActiveXObject("ShockwaveFlash.ShockwaveFlash.'+f+'")'))bm.m=f}catch(e){;}
            try{bm.y=bmN.javaEnabled()?1:0}catch(e){;}
            try{bmS=screen;bm.v^=bm.d=bmS.colorDepth||bmS.pixelDepth;bm.v^=bm.r=bmS.width}catch(e){;}
            r=bmD.referrer.replace(/^w+:\/\//,'');if(r&&r.split('/')[0]!=window.location.host){bm.f=escape(r).slice(0,400);bm.v^=r.length}
            bm.v^=window.location.href.length;for(var x in bm) if(/^[ovstcnwmydrf]$/.test(x)) bs[i++]=x+bm[x];
            bmD.write('<sc'+'ript type="text/javascript" language="javascript" src="//c.bigmir.net/?'+bs.join('&')+'"></sc'+'ript>');
            //-->
        </script>
        <noscript>
            <a href="http://www.bigmir.net/" target="_blank"><img src="//c.bigmir.net/?v16942487&s16942487&t2" width="88" height="31" alt="bigmir)net TOP 100" title="bigmir)net TOP 100" border="0" /></a>
        </noscript>
        <!--bigmir)net TOP 100-->
		
		<!--LiveInternet counter--><script type="text/javascript"><!--
			document.write("<a href='//www.liveinternet.ru/click' "+
			"target=_blank><img src='//counter.yadro.ru/hit?t12.6;r"+
			escape(document.referrer)+((typeof(screen)=="undefined")?"":
			";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
			screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
			";"+Math.random()+
			"' alt='' title='LiveInternet: показано число просмотров за 24"+
			" часа, посетителей за 24 часа и за сегодня' "+
			"border='0' width='88' height='31'><\/a>")
		//--></script><!--/LiveInternet-->

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
    <a href="/">math-deque.rhcloud.com</a> &copy; 2016</a>
</div>
</body>
</html>