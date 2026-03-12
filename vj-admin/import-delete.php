<?php
   include("admin.php");
   error_reporting(0);
 
   function get_feedid($id) {
      global $vjdb;
      $query = "SELECT ID FROM $vjdb->feeds WHERE ID = $id";
      return $vjdb->get_var($query);
   }

   function delete_feed($id) {
      global $ajax;
      echo "<form method=\"post\">";
      echo "<h2>刪除 RSS 匯入來源</h2>";
      echo "<p>您確定要刪除這筆資料嗎？</p>";
      echo "<input type=\"submit\" name=\"submit\" value=\"確定刪除\"/>";
      echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\"/>";
      if($ajax) {
	 echo "<input type=\"hidden\" name=\"ajax\" value=\"1\"/>";
      }
      echo "</form>";
   }


   $id=$_GET['id'];

   if(!get_feedid($id)) {
      admin_header("刪除 RSS 匯入來源");
      echo "<h2>錯誤</h2>";
      echo "<p>系統中並沒有符合這個代號的 RSS 匯入來源。</p>";
      admin_footer();
   } else if($_POST['submit']) {
      $query = "DELETE FROM $vjdb->cat WHERE ID='$id'";
      admin_header("刪除 RSS 匯入來源");
      if( $vjdb->query($query) ) {
	 echo "<h2>刪除完畢</h2>";
	 echo "<p>已經將您所指定的 RSS 匯入來源刪除了。</p>";
      } else {
	 echo "<h2>錯誤</h2>";
	 echo "<p>您所指定的 RSS 匯入來源無法刪除。</p>";
      }
      admin_footer();
   } else if($_GET['ajax']==2) {
      $query = "DELETE FROM $vjdb->feeds WHERE ID='$id'";
      if( $vjdb->query($query) ) {
	 echo "1";
      } else {
	 echo "0";
      }
   } else {
      admin_header("刪除 RSS 匯入來源");
      delete_feed($id);
      admin_footer();
   }
?>

