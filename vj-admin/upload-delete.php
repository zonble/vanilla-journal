<?php 
   include("admin.php"); 
   $uploadurl = vjinfo('url').$upload;
   $refer = $_GET['refer'];

   $error = 0;
   $submit = $_POST['submit'];
   admin_header("刪除圖片");
   echo "<h2>刪除圖片</h2>";
   if(empty($submit)) {
      $id = $_GET['id'];
      if($id) {
	 $query = "SELECT * FROM $vjdb->images WHERE ID='$id' LIMIT 1";
	 $info = $vjdb->get_row($query, ARRAY_A);
	 if($info) {
	    $image = new image($info);
	    echo "<p>您確定要刪除這張圖片嗎？</p>";
	    echo "<p>";
	    $image->thumb_html();
	    echo "</p>";
	 ?>
	 <form method="post" action="upload-delete.php">
	 <input type="hidden" name="id" value="<?php echo $id?>" />
	 <input type="submit" name="submit" value="確定刪除"/>
<?php if(!$ajax) { ?>
	 <input type="button" name="cancel" value="取消，回到上頁" onclick="history.go(-1);" />; 
<?} else { ?>
	 <input type="hidden" name="ajax" value="1"/>
<?} ?>
	 </form>
	 <?php
	 } else {
	    admin_die("對不起，沒有符合這個 ID 的照片。");
	 }
      } else {
	 admin_die("請指定要編輯的照片 ID");
      }
   } else {
      $id = $_POST['id'];
      $query = "SELECT * FROM $vjdb->images WHERE ID='$id' LIMIT 1";
      $info = $vjdb->get_row($query, ARRAY_A); 
      $image = new image($info);

      if($info) {
	 $query = "DELETE FROM $vjdb->images WHERE ID='$id'";
	 if($vjdb->query($query)) {
	    @unlink($image->filepath);
	    @unlink($image->thumbpath);
	    echo "<p>刪除完畢。</p>";
	 }
      } else {
	 admin_die("<h2>執行錯誤</h2>");
      }
   }
   admin_footer();
?>
