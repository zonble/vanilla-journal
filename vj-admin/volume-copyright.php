<?php
   include("admin.php");

   function load_copyright(){
      global $vjdb;
      $query = "SELECT COPYRIGHT FROM $vjdb->volumes WHERE COPYRIGHT != '' ORDER BY CREATE_DATE DESC LIMIT 1";
      $copyright = $vjdb->get_var($query);
      return $copyright;
   }

   if($ajax) {
      $copyright = load_copyright();
      if($copyright) {
	 header("Content-type: text/html; charset=utf-8");
	 echo $copyright;
      }
   }
?>