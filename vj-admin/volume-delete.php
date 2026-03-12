<?php
   include("admin.php");

   function delete_vol($postvar){
      global $vjdb;
      global $uploadpath;

      $id = $postvar['id'];
      if(empty($id)) {
	 return;
      }
      $volinfo = $vjdb->get_row("SELECT * FROM $vjdb->volumes WHERE ID=$id",ARRAY_A);
      if($volinfo){
	 $query = "SELECT * FROM $vjdb->images WHERE VOLUMEID ='$id'";
	 $results = $vjdb->get_results($query, ARRAY_A);
	 if($results) {
	    foreach($results as $image) {
	       $myimage = new image($image);
	       @unlink($myimage->filepath);
	       @unlink($myimage->thumbpath);
	    }
	 }
	 $query = "DELETE FROM $vjdb->images WHERE VOLUMEID ='$id'";
	 $vjdb->query($query);
	 $query = "DELETE FROM $vjdb->volumes WHERE ID='$id'";
	 $vjdb->query($query);
	 $query = "SELECT ID FROM $vjdb->post WHERE VOLUME='$id'";
	 $results = $vjdb->get_results($query, ARRAY_A);
	 if($results) {
	    foreach($results as $post) {
	       $postid = $post['ID'];
	       $query = "SELECT * FROM $vjdb->images WHERE POSTID ='$postid'";
	       $results2 = $vjdb->get_results($query, ARRAY_A);
	       if($results2) {
		  foreach($results2 as $image) {
		     $myimage = new image($image);
		     @unlink($myimage->filepath);
		     @unlink($myimage->thumbpath);
		  }
	       }
	       $query = "DELETE FROM $vjdb->images WHERE POSTID='$postid'";
	       $vjdb->query($query);
	       $query = "SELECT * FROM $vjdb->attaches WHERE POSTID ='$postid'";
	       $results2 = $vjdb->get_results($query, ARRAY_A);
	       if($results2) {
		  foreach($results2 as $attach) {
		     $myattach = new attach();
		     $myattach->get_attachinfo($attach);
		     @unlink($myattach->filepath);
		     @unlink($myattach->thumbpath);
		  }
	       }
	       $query = "DELETE FROM $vjdb->attaches WHERE POSTID='$postid'";
	       $vjdb->query($query);
	    }
	 }
	 $query = "DELETE FROM $vjdb->post WHERE VOLUME='$id'";
	 $vjdb->query($query);
      }
      header("Location: ".$postvar['refer']);
      die();
   }

   $action = $_POST['action'];

   if($action =="delete")  {
      delete_vol($_POST);
   }

   $id = $_POST['id'];
   if(empty($id)) {
      $id = $_GET['id'];
   }
   $volinfo = $vjdb->get_row("SELECT * FROM $vjdb->volumes WHERE ID='$id'",ARRAY_A);
   if(empty($volinfo)) {
      $str = "<h2>錯誤！</h2>\n";
      $str .= "<p>系統中目前沒有您所指定代號的期刊資料！</p>";
      admin_die($str, "在刪除期刊資料時發生錯誤！");
   } else {
   $pagetitle="刪除《". vjinfo('title')."》第". $volinfo['ALIAS']."期";
   admin_header($pagetitle);
?>

<div class="wrap">
    <h2>刪除：《<?php info('title') ?>》第 <?php echo $volinfo['ALIAS']; ?> 期 </h2>
    <p>您確定要刪除《<?php info('title') ?>》第 <?php echo $volinfo['ALIAS']; ?> 期嗎？</p>
    <p>如果刪除的話，這一期電子報的所有文章、圖片，也會隨之一同刪除。</p>
    <form method="post" action="volume-delete.php" />
    <input type="hidden" name="id" value="<?php echo $volinfo['ID']; ?>" />
    <input type="hidden" name="action" value="delete" />
    <?php $refer = $_SERVER['HTTP_REFERER'];?>
    <input type="hidden" name="refer" value="<?php echo $refer;?>" /></p>
    <p><input type="submit" value="確定刪除" /></p>
    <p><a href="<?php echo $refer?>">不刪除，回到前一頁</a></p>
    </form>
</div>

<?php
   }
   admin_footer();
?>