<?php
include("admin.php"); 
admin_header("上傳附件"); ?>

<h2>上傳附件</h2>
<?php 
   $postid = $_GET['postid'];
   if(empty($postid)) {
      echo "<h2>錯誤！</h2>";
      echo "<p>請指定您想要上傳的檔案是給哪篇文章用的！</p>";
   } else {
      attach_form($postid);
   }
?>
<?php admin_footer() ?>