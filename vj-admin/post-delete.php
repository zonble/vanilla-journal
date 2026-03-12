<?php
   include("admin.php");

   function delete_post($postvar){
      global $vjdb;

      $postid = $postvar['id'];
      $post = new post($postid);
      if(!$post->post_exist()) {
	 admin_die("<h2>執行錯誤！</h2>");
	 return;
      }
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

      $query = "DELETE FROM $vjdb->post WHERE ID='$postid'";
      $vjdb->query($query);
      $refer = $postvar['refer'];
      if(strstr($refer, "post-edit.php")) {
	 header("Location: volume-edit.php?volume=".$post->post_vol);
      } else header("Location: ".$postvar['refer']);
      die();
   }

   $action = $_POST['action'];

   if($action =="delete")  {
      delete_post($_POST);
   }

  //  $id = $_POST['id'];
   if(empty($id)) { $id = $_GET['id']; }
   $post = new post($id);

   if(!$post->post_exist()) {
      $str = "<h2>錯誤！</h2>\n";
      $str .= "<p>系統中目前沒有您所指定代號的文章！</p>";
      admin_die($str, "在刪除文章時發生錯誤！");
   } else {
      $pagetitle="刪除文章";
      admin_header($pagetitle);
   ?>

   <div class="wrap">
<h2>刪除文章：〈<?php echo $post->post_topic; ?>〉 </h2>
<p>您確定要刪除〈<?php echo $post->post_topic?>〉這篇文章嗎？</p>
   <p>如果刪除的話，這篇文章的所有圖片、附件，也會隨之一同刪除。</p>
   <form method="post" action="post-delete.php" />
<input type="hidden" name="id" value="<?php echo $post->post_id ?>" />
   <input type="hidden" name="action" value="delete" />
<?php $refer = $_SERVER['HTTP_REFERER'];?>
<input type="hidden" name="refer" value="<?php echo $refer;?>" />
   <p><input type="submit" value="確定刪除" /></p>
<p><a href="<?php echo $refer?>">不刪除，回到前一頁</a></p>
   </form>
   </div>

   <?php
   }
   admin_footer();
?>
