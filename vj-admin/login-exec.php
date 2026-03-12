<?php
   $loginpage = 1;
   include("admin.php");
   $submit = $_POST['submit'];
   $mypass = $_POST['password'];
   $password = vjinfo('password');

   if($submit) {
      if(md5($mypass) == $password){
	 session_register('password');
	 $_SESSION['password'] = $password;
	 header("Location: index.php");
      } else {
	 header("Location: login.php?wrong=1");
      }
    }
    header("Location: index.php");
?>
