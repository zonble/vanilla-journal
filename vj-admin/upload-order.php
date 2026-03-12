<?php
   include("admin.php");

   function image_list($id) {
      global $vjdb, $upload;
      $uploadurl = vjinfo('url').$upload;
      $query = "SELECT * FROM $vjdb->images WHERE POSTID='$id' ORDER BY 'IMAGE_ORDER' ASC ,ID DESC";
      $result = $vjdb->get_results($query, ARRAY_A);
      if(empty($result)) {
	 return;
      }
      foreach($result as $image){
	 $myimage = new image($image);
	 $myimage->sq_html();
      }
      echo '<br clear="all" />';
   }

   $error = 0;
   $submit = $_POST['submit'];
   $postid = $_POST['postid'];
   $uploadurl = $config['image_url'];
   if($submit) {
      foreach($_POST as $key => $value) {
	 if(strstr($key, "image-")) {
	    $id = str_replace("image-", "", $key);
	    $query = "SELECT POSTID, ID FROM $vjdb->images WHERE ID='$id'";
	    $result = $vjdb->get_row($query, ARRAY_A);
	    //print_r($result);
	    if(empty($result['ID'])) {
	       continue;
	    }
	    if($result['POSTID'] != $postid) {
	       continue;
	    }  
	    $query = "UPDATE $vjdb->images SET IMAGE_ORDER ='$value' WHERE ID='$id' LIMIT 1";
	    $vjdb->query($query);
	 }
      }
      if($ajax) {
	 die("1");
      }
      admin_header("更新照片排列順序");
?>
<h2>改變文章中的照片順序</h2>
<p>您在文章中的照片順序已經調整成以下的排列：</p>
<?
      image_list($postid);
   }
?>
<?php admin_footer(); ?>