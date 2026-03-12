<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-TW">
<!--
Copyright: Daemon Pty Limited 2006, http://www.daemon.com.au
Community: Mollio http://www.mollio.org $
License: Released Under the "Common Public License 1.0", 
http://www.opensource.org/licenses/cpl.php
License: Released Under the "Creative Commons License", 
http://creativecommons.org/licenses/by/2.5/
License: Released Under the "GNU Creative Commons License", 
http://creativecommons.org/licenses/GPL/2.0/
-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php vj_title() ?></title>
    <link rel="stylesheet" media="screen" type="text/css" href="<?php info('url-css-screen'); ?>" />
    <!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="<?php info('url') ?>theme/default/ie6_or_less.css" />
<![endif]-->
    <link rel="stylesheet" media="screen" type="text/css" href="<?php echo vjinfo('url'); ?>vj-style/lightbox.css " />
    <link rel="stylesheet" media="print" type="text/css" href="<?php info('url-css-print'); ?>" />
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php
info('rss');?>" />
    <script type="text/javascript">
    var HOST = "<?php info('url') ?>";
    </script>
    <script type="text/javascript" src="<?php info('url-js-shared');?>prototype.js"></script>
    <script type="text/javascript" src="<?php info('url-js-shared');?>scriptaculous.js?load=effects"></script>
    <script type="text/javascript" src="<?php info('url-js-shared');?>lightbox.js"></script>
    <script type="text/javascript" src="<?php info('url-js');?>"></script>
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta name="robots" content="noarchive" />
    <?php show_archive() ?>
</head>

<body id="type-c">
    <?php
   if(is_logined() && !$is_email) {
      vj_adminbar();
   }
?>
    <div id="wrap">
        <div id="header">
            <div id="site-name"><?php echo vjinfo('title'); ?></div>
            <div id="search">
                <form method="post" action="search.php">
                    <input size="10" type="text" id="keyword" name="keyword" value="<?php echo $_POST['keyword']; ?>" />
                    <input type="submit" name="submit" value="搜尋" class="f-submit" />
                </form>
            </div>
            <?php
   if(!$is_email) {
      include("menu.php");
   } else {
      include("emailmenu.php");
   }
?>
        </div>
        <div id="content-wrap">
            <div id="utility">
                <?php vj_volalias("<h3>","</h3>"); ?>
                <?php vj_voldate("<p>","出刊</p>"); ?>
                <?php volume_copyright("<div>","</div>"); ?>
            </div>
            <div id="content">