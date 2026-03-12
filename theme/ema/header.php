<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-TW">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php vj_title() ?></title>
<link rel="stylesheet" media="screen" type="text/css" href="<?php info('url-css-screen'); ?>" /> 
<link rel="stylesheet" media="screen" type="text/css" href="<?php echo vjinfo('url'); ?>vj-style/lightbox.css "/>
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
<body>
<?php
   if(is_logined() && !$is_email) {
      vj_adminbar();
   }
?>
<div id="rap">
<div id="header">
<?php
   if(!$is_email) {
      include("menu.php");
   } else {
      include("emailmenu.php");
   }
?>
<h1><?php echo vjinfo('title'); ?></h1>
<div id="date">
   <p id="date-vol"><?php vj_volalias(); ?></p>
   <p id="date-vol"><?php vj_voldate(); ?></p>
</div>
</div>

<div id="main">
<div id="content">
