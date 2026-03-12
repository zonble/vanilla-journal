<?php
   include("admin.php"); 

   if($ajax) { 
      header("Content-type: text/html; charset=utf-8");
      sel_cat('cat', 0);
   }

?>