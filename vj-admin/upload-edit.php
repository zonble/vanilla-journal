<?php
   $pagetitle = "編輯照片資訊";
   include("admin.php");
?>
<?php 
   $error = 0;
   $submit = $_POST['submit'];
   $uploadurl = $config['image_url'];
   if(empty($submit)) {
      admin_header();
      $refer = $_SERVER['HTTP_REFERER'];
      echo "<h2>編輯照片資訊</h2>";
      $id = $_GET['id'];
      if($id) {
	 $query = "SELECT * FROM $vjdb->images WHERE ID='$id'";
	 $info = $vjdb->get_row($query, ARRAY_A);
	 if($info) {
	    $image = new image($info);
	 ?>
	<form method="post" action="upload-edit.php">
     <?php if($ajax) { ?>
     <input type="hidden" name="ajax" value="1" />
     <?php  } ?>
     <p><?php $image->thumb_html() ?></p>
     <p>圖說：<input size="50" type="text" name="tagline" value="<?php echo $image->tagline ?>" class="myinput" /></p>
	<p>是否顯示在版面上？
     <input type="radio" name="display" value="1" id="dis1" <?php if($image->display) echo ' checked="checked"';?>/> <label for="dis1">是</label>
     <input type="radio" name="display" value="0" id="dis0" <?php if(!$image->display) echo ' checked="checked"';?> /> <label for="dis0">否</label>
	</p>
     <input type="hidden" name="id" value="<?php echo $id; ?>"/>
     <input type="hidden" name="myreferer" value="<?php echo $refer ;?>"/>
     <p><input type="submit" name="submit" value="更新圖說" /></p>
	   </form>
	   <div>
	   <h3>本圖的各項資訊</h3>
	   <ul style="font-size: 9pt;">
	<li>大圖網址：<?php echo $image->filelink; ?></li>
	<li>縮圖網址：<?php echo $image->thumblink; ?></li>
	<li>圖片尺寸：大圖為 <?php echo $image->w ?> pixel x <?php $image->h ?> pixel 、
	縮圖為 <?php echo $image->tw;?> pixel x <?php echo $image->th ?> pixel </li>
	<li>在別的地方使用本圖的 <abbr title="Hypertext Markup Language">HTML</abbr> 語法：<br/>
     <textarea cols="70" style="font-size: 9pt; width: 90%; margin: 3px;"><?php $image->link_html() ?></textarea>
	</li>
     <li>上傳時間：<?php echo date("Y-m-d H:i:s", $image->upload_date); ?></li>
	   </ul>
	   </div>
	   <?
	 } else {
	    echo "對不起，沒有符合這個 ID 的圖片。";
	 }
      } else {
	 echo "請指定要編輯的圖片 ID";
      }
   } else {
      $id = $_POST['id'];
      $tagline = $_POST['tagline'];
      $display = $_POST['display'];
      $query = "UPDATE $vjdb->images SET TAGLINE ='$tagline', DISPLAY = '$display'  WHERE ID='$id'";
      $vjdb->get_var($query);
      $refer = $_POST['myreferer'];
      if($ajax) {
	 admin_header();
	 echo "<h2>更新完畢！</h2>";
	 echo "<p>您現在可以關閉視窗了。</p>";
      } else if($refer) {
	 header("Location: $refer");
      } else {
	 admin_header();
	 echo "<h2>更新完畢！</h2>";
	 echo "<p>您現在可以關閉視窗了。</p>";
      }
   }
?>
<?php admin_footer(); ?>
