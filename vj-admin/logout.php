<?php
   $loginpage = 1;
   include("admin.php");
   session_destroy();
   header("Location: ".vjinfo('url'));
?>