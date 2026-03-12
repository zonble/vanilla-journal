<?php
   include("admin.php");

   $action = $_POST['action'];
   $refer = $_POST['refer'];
   $topublish = $_POST['topublish'];
   $id = $_POST['id'];
   $submit = $_POST['submit'];

   if($action =="publish" && $submit)  {
      $query = "UPDATE $vjdb->volumes SET PUBLISHED='$topublish' WHERE ID='$id'";
      $vjdb->query($query);
   } 
   if($refer) {
      header("Location: $refer");
   }
   admin_die("<p>您不可以直接瀏覽這個檔案！</p>", "錯誤");
?>
