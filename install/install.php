<?php
$config = array();
require("../config.php");

function step_one_form($title="", $url="") {
?>
   <form method="post">
   <input type="hidden" value="step1"/>
   <h3>請輸入安裝 Vanilla Journal 所需要的基本資訊：</h3>
   <p>（這些設定日後都還可以修改，也請您務必記住您現在輸入的密碼。）</p>
   <table>
   <tr><td>您的期刊名稱：</td><td><input name="title" type="text" value="<?php echo $title; ?>" size="50"/></td></tr>
   <tr><td>您的期刊網址：</td><td><input name="url" type="text" value="<?php echo $url;?>" size="50"/></td></tr>
  <tr><td>您的登入密碼：</td><td><input name="password" type="password" value="" size="50"/></td></tr>
  <tr><td>請再輸入一次密碼：</td><td><input name="password2" type="password" value="" size="50"/></td></tr>
   <tr><td>&nbsp;</td><td><input type="submit" name="submit" value="繼續安裝"/></td></tr>
   </table>
   <input name="action" type="hidden" value="step1" />
   </form>
<?php }

function install_footer() {
?>
   <p class="credit">2006 Weizhong Yang</p>
   </div>
   </body>
   </html>
<?php }

function install_header() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-TW">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vanilla Journal - 香草期刊系統 » 安裝程式</title>
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Content-Language" content="zh-tw" />
<meta name="robots" content="noarchive" />
<style type="text/css">
body {
   font-size: 12pt;
   font-family: Arial, sans-serif;
   margin: 0;
   padding: 0;
}

h1 {
   background: #666;
   color: #FFF;
   margin-top: 0;
   padding: 10px;
   text-align: center;
}

.wrap {
   width: 750px;
   margin: 10px auto;
}

.credit {
   background: #666;
   color: #FFF;
   font-size: 10pt;
   padding: 3px;
   text-align: center;
}
</style>
</head>
<body>
<h1> Vanilla Journal 安裝程式</h1>
<div class="wrap">
<?php } 

$submit = $_POST['submit'];
$action = $_POST['action'];
if($submit && $action =='step1'){
   $title = $_POST['title'];
   $url = $_POST['url'];
   $password = $_POST['password'];
   $password2 = $_POST['password2'];
   if(empty($url)) {
      install_header();
      echo "<h2>錯誤</h2>";
      echo "<p>請輸入網址！</p>";
      step_one_form($title, $url);
      install_footer();
   } else if(!$password || !$password2) {
      install_header();
      echo "<h2>錯誤</h2>";
      echo "<p>請輸入密碼！</p>";
      step_one_form($title, $url);
      install_footer();
   } else if($password != $password2) {
      install_header();
      echo "<h2>錯誤</h2>";
      echo "<p>兩次密碼不同！</p>";
      step_one_form($title, $url);
      install_footer();
   } else {
      require("../vj-include/vj.db.php");
      require("vjsql.php");
      install_header();
      install_db($title, $url, $password);
      error_reporting(0);
      require("../vj-include/vj.php");
   if(!vjinfo('url')) {   
?>
<h2>安裝時發生錯誤</h2>
<p>安裝時發生問題，請檢查您的資料庫設定。</p>
<?php } else { ?>
<h2>安裝完畢</h2>
<p>我們已經在資料庫中加入了必要的欄位，如果沒有什麼意外的話（意思就是，因為寫 code 的人時間不多，沒力氣多做一些檢查，很有可能有別的意外），您現在已經可以開始使用 Vanilla Journal 了。</p>
<p>您現在可以：</p>
<ul>
<li><a href="../index.php">前往期刊網站首頁。</a></li>
<li><a href="../vj-admin/login.php">前往管理介面，開始新增內容或修改設定。</a></li>
</ul>
<?
      }
      install_footer();
   }
} else {
   include("../vj-include/vj.php");
   $vj = new vj();
   if(vjinfo('url')) {
      install_header();
      echo "<h2>已成功安裝</h2><p>您已經成功安裝了 Vanilla Journal，不需要重新安裝。</p>"; 
      install_footer();
      die();
   }
   install_header();
?>
<h2>歡迎使用 Vanilla Journal！</h2>
<p><strong>Vanilla Journal</strong> 這套系統，可以幫助您輕鬆建立一套屬於您自己的線上期刊，包含上傳文章、調整分類順序、上傳圖片與附件…等各項功能。而首先，我們需要在 MySQL 資料庫中，加入一些必要的資料庫欄位。</p>
<p>如果您需要安裝的相關協助，您可以查看 Vanilla Journal 專案網頁上的<a href="http://code.google.com/p/vanilla-journal/wiki/InstallVanillaJournal">安裝說明</a>。</p>
<p>在新增資料庫之前，我們需要先問您一些關於您的期刊網站的基本問題。</p>
<?php
$schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on'
) ? 'https://' : 'http://';
$guessurl = str_replace('install', '', $schema.$_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
?>
<?php 
   step_one_form("Vanilla Journal", $guessurl);
   install_footer();
} ?>
</div>
