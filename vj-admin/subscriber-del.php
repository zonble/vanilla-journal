<?php
   include("admin.php");

   function delete_subscriber($postvar){
      global $vjdb;
      global $uploadpath;
      global $ajax;

      $id = $postvar['id'];
      $result = $vjdb->get_results("SELECT * FROM $vjdb->subscribers WHERE ID=$id",ARRAY_A);
      $subscriber = $result[0];
      if($subscriber){
	 $query = "DELETE FROM $vjdb->subscribers WHERE ID='$id'";
	 $vjdb->get_var($query);
      }
      if($ajax) {
	 admin_header();
	 echo "<h2>已經成功刪除訂戶</h2>";
	 echo "<p>您現在可以關閉視窗。</p>";
	 admin_footer();
	 die();
      } else {
	 header("Location: ". $postvar['refer']);
      }
   }

   $pagetitle="刪除訂戶";
   $action = $_POST['action'];

   if($action =="delete")  {
      delete_subscriber($_POST);
   }

   $id = $_POST['id'];
   if(empty($id)) $id = $_GET['id'];
   $result = $vjdb->get_results("SELECT * FROM $vjdb->subscribers WHERE ID=$id LIMIT 1",ARRAY_A);
   $subscriber = $result[0];
   if(empty($subscriber)) {
      $str = "<h2>錯誤！</h2>\n";
      $str .= "<p>系統中目前沒有您所指定代號的訂戶！</p>";
      admin_die($str."刪除訂戶時發生錯誤！");
   } else {
   admin_header();
?>

<h2>刪除訂戶</h2>
<p>您確定要刪除 <?php echo $subscriber['EMAIL'] ?> 這個訂戶嗎？</p>
<form method="post" action="subscriber-del.php" />
<input type="hidden" name="id" value="<?php echo $subscriber['ID']; ?>" />
<input type="hidden" name="action" value="delete" />
<?php $refer = $_SERVER['HTTP_REFERER'];?>
<input type="hidden" name="refer" value="<?php echo $refer;?>" /></td>
</p>
<p><input type="submit" value="確定刪除" /></td>
</p>
<?php if(!$ajax) { ?>
<p><a href="<?php echo $refer?>">不刪除，回到前一頁</a></p>
<?php } else { ?>
<input type="hidden" name="ajax" value="1" /></td>
</p>
<?php } ?>
</form>
</div>

<?php
   }
   admin_footer();
?>