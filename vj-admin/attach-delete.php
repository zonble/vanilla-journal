<?php 
   include("admin.php"); 
   admin_header("刪除附件檔案");
   echo "<h2>刪除附件檔案</h2>";
   $submit = $_POST['submit'];
   if(empty($submit)) {
      $id = $_GET['id'];
      if($id) {
	 $query = "SELECT * FROM $vjdb->attaches WHERE ID='$id' LIMIT 1";
	 $info = $vjdb->get_row($query, ARRAY_A);
	 if($info) {
	    $attach = new attach();
	    $attach->get_attachinfo($info);
	    echo "<p>您確定要刪除這個檔案嗎？</p>";
	    echo "<p>";
	    $attach->link_html();
	    echo "</p>";
	 ?>
<form method="post" action="attach-delete.php">
    <input type="hidden" name="id" value="<?php echo $id?>" />
    <input type="submit" name="submit" value="確定刪除" />
    <?php if(!$ajax) { ?>
    <input type="button" name="cancel" value="取消，回到上頁" onclick="history.go(-1);" />;
    <?} else { ?>
    <input type="hidden" name="ajax" value="1" />
    <?} ?>
</form>
<?php
	 } else {
	    admin_die("對不起，沒有符合這個 ID 的檔案。");
	 }
      } else {
	 admin_die("請指定要編輯的檔案 ID");
      }
   } else {
      $id = $_POST['id'];
      $query = "SELECT * FROM $vjdb->attaches WHERE ID='$id' LIMIT 1";
      $info = $vjdb->get_row($query, ARRAY_A); 
      $attach = new image($info);

      if($info) {
	 $query = "DELETE FROM $vjdb->attaches WHERE ID='$id'";
	 if($vjdb->query($query)) {
	    @unlink($attach->filepath);
	    @unlink($attach->thumbpath);
	    echo "<p>刪除完畢。</p>";
	 }
      } else {
	 admin_die("<h2>執行錯誤</h2>");
      }
   }
   admin_footer();
?>