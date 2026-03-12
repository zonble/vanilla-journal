<?php
   $loginpage = 1;
   $wrong = $_GET['wrong'];
   include("admin.php");
   if(is_logined()) {
      header("Location: index.php");
   } //如果已經登入，就直接跳往管理首頁
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-TW">

<head profile="http://gmpg.org/xfn/1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-tw" />
<meta name="generator" content="Vanilla Journal" /> 
<meta name="robots" content="noindex,nofollow" />
<title><?php info('title'); ?> » 登入系統</title>
<link rel="stylesheet" type="text/css" media="screen" href="admin.css" />
</head>
<body onload="document.loginform.password.focus();">
<div id="login">
<form name="loginform" action="login-exec.php" method="post" style="text-align: center;" >
<h2>登入系統</h2>
<p>請輸入登入密碼</p>
<?php if($wrong) echo "<p>密碼輸入錯誤</p>"; ?>
<p><input type="password" name="password" style="font-size: 14pt; text-align: center;"/></p>
<p><input type="submit" name="submit" id="submit" value="登入" style="font-size:14pt;"/></p>
<p><a href="<?php echo vjinfo('url');?>">回到網站首頁</a></p>
</form>

<?php
   admin_footer();
?>
