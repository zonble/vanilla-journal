<?php 
   global $pagetitle;

   if($pagetitle) {
      $pagetitle = vjinfo('title'). " » ". $pagetitle;
   } else {
      $pagetitle = vjinfo('title');
   }
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
   header("Cache-Control: no-store, no-cache, must-revalidate");
   header("Cache-Control: post-check=0, pre-check=0", false);
   header("Pragma: no-cache");
   header("Content-type: text/html; charset=utf-8"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-TW">

<head profile="http://gmpg.org/xfn/1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-tw" />
<meta name="generator" content="Vanilla Journal" /> 
<meta name="robots" content="noindex,nofollow" />
<title><?php echo $pagetitle; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="admin.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo vjinfo('url'); ?>vj-style/lightbox.css "/>
<!-- tinyMCE -->
<script type="text/javascript">
   var HOST = "<?php info('url') ?>";
</script>
<script type="text/javascript" src="<?php echo vjinfo('url');?>vj-script/prototype.js"></script>
<?php if($wysiwyg) { ?>
<script type="text/javascript" src="<?php echo vjinfo('url');?>vj-admin/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo vjinfo('url');?>vj-script/scriptaculous.js"></script>
<script type="text/javascript">
tinyMCE.init({
   mode : "textareas",
   theme:"advanced",
   language : "zh_tw_utf8",
   browsers : "msie,gecko",
   editor_selector : "mceEditor",
   plugins : "table,advimage,advlink,emotions,iespell,zoom,flash,searchreplace,paste,directionality,fullscreen",
   theme_advanced_buttons1_add : "fullscreen",
   theme_advanced_buttons2_add_before: "pastetext,pasteword,separator",
   theme_advanced_buttons3 : "tablecontrols,separator",
   theme_advanced_buttons3_add : "iespell,flash,separator,forecolor,backcolor",
   theme_advanced_toolbar_location : "top",
   theme_advanced_toolbar_align : "left",
   theme_advanced_statusbar_location : "bottom",
   theme_advanced_resizing : true,
   theme_advanced_resize_horizontal : false
});
</script>
<!-- /tinyMCE -->
<?php } else { ?> 
<script type="text/javascript" src="<?php echo vjinfo('url');?>vj-script/scriptaculous.js"></script>
<?php } ?> 
<script type="text/javascript" src="<?php echo vjinfo('url');?>vj-script/lightbox.js"></script>
<script type="text/javascript" src="admin-browser.js"></script>
<script type="text/javascript" src="admin.js"></script>
</head>
<body>
<div id="main">
<?php
   if(is_logined() && $_COOKIE['vjquicklinkhide'] != 'hide') {
      $query = "SELECT ID, ALIAS FROM $vjdb->volumes WHERE PUBLISHED='0' ORDER BY CREATE_DATE DESC LIMIT 1";
      $results = $vjdb->get_results($query, ARRAY_A);
      if($results) {
	 $info = $results[0];
	 $myid = $info['ID'];
	 echo '<div id="quicklink">';
	 echo '<form method="post" action="volume-publish.php">';
	 echo '<strong><a href="#" id="quicklink-hide">快速連結</a></strong>：您最新新增而還沒有上線的期刊為「第 '.$info['ALIAS'].' 期」 - ';
	 echo '<a href="volume-info.php?id='.$myid.'">設定資料</a> | ';
	 echo '<a href="post-add.php?volume='.$myid.'">新增文章</a> | ';
	 echo '<a href="import.php?volume='.$myid.'">匯入文章</a> | ';
	 echo '<a href="volume-edit.php?volume='.$myid.'">管理文章</a> | ';
	 echo '<a href="../index.php?volume='.$myid.'">查看網頁</a> | ';
	 echo '<input type="hidden" name="topublish" value="1" />';
	 echo '<input type="hidden" name="id" value="'.$myid.'" />';
	 $refer = $_SERVER['REQUEST_URI'];
	 echo '<input type="hidden" name="refer" value="'.$refer.'" />';
	 echo '<input type="hidden" name="action" value="publish" />';
	 echo '<input type="submit" name="submit" value="上線" />';
	 echo '</form>';
	 echo '</div>';
      }
   }
?>
<?php if(!$loginpage) { ?>
<div id="header">
<h1>
<?php echo vjinfo('title') ?><br/>
期刊網站管理介面
</h1>
<div class="nav">
<p>基本頁面：
<a href="index.php" title="管理首頁">前往管理首頁</a>
<span class="sep">|</span>
<a href="../" title="電子報網站首頁">前往網站首頁</a>
<span class="sep">|</span>
<a href="logout.php" title="登出管理介面">登出管理介面</a>
</p>
<p>內容管理：
<a href="volumes.php" title="期數文章維護">期數文章維護</a>
<span class="sep">|</span>
<a href="volume-add.php" title="新增一期期刊">新增一期期刊</a>
<span class="sep">|</span>
<a href="cats.php" title="文章單元設定">文章單元設定</a>
</p>
<p>發行管理：
<a href="subscribers.php" title="訂戶資料管理">訂戶資料管理</a>
<span class="sep">|</span>
<a href="subscriber-add.php" title="新增一名訂戶">新增一名訂戶</a>
<span class="sep">|</span>
<a href="mail.php" title="執行寄送作業">執行寄送作業</a>
</p>
<p>系統管理：
<a href="info.php" title="系統基本設定">系統基本設定</a>
<span class="sep">|</span>
<a href="file-edit.php" title="系統輔助說明">編輯網頁範本</a>
<span class="sep">|</span>
<a href="password.php" title="修改系統密碼">修改系統密碼</a>
</p>
</div>
<br clear="all" />
<br clear="right" />
</div>
<?php } ?>
