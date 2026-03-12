<?php include("admin.php"); ?>
<?php 
   $submit = $_POST['submit'];
   if(empty($submit)) {
      admin_header("編輯檔案資訊");
      $refer = $_SERVER['HTTP_REFERER'];
      echo "<h2>編輯檔案資訊</h2>";
      $id = $_GET['id'];
      if($id) {
	 $query = "SELECT * FROM $vjdb->attaches WHERE ID='$id'";
	 $info = $vjdb->get_row($query, ARRAY_A);
	 if($info) {
	    $attach = new attach();
	    $attach->get_attachinfo($info);
	 ?>
	<form method="post" action="attach-edit.php">
	<p>檔案：<?php $attach->link_html(); ?></p>
     <p>檔案說明：<input size="50" type="text" name="tagline" value="<?php echo $attach->tagline ?>" class="myinput" /></p>
	<p>是否顯示在版面上？
     <input type="radio" name="display" value="1" id="dis1" <?php if($attach->display) echo ' checked="checked"';?>/> <label for="dis1">是</label>
     <input type="radio" name="display" value="0" id="dis0" <?php if(!$attach->display) echo ' checked="checked"';?> /> <label for="dis0">否</label>
	</p>
     <input type="hidden" name="id" value="<?php echo $id; ?>"/>
     <input type="hidden" name="myreferer" value="<?php echo $refer ;?>"/>
     <?php if($ajax) { ?>
     <input type="hidden" name="ajax" value="1"/>
     <? } ?>
     <p><input type="submit" name="submit" value="更新檔案說明等資訊" /></p>
	   </form>
	   <div>
	   <h3>這個檔案的各項資訊</h3>
	   <ul style="font-size: 9pt;">
	<li>檔案網址：<?php echo $attach->filelink; ?></li>
	<li>檔案大小：<?php echo size_hum_read($attach->filesize); ?></li>
	<li>檔案類型（MIME資訊）：<?php echo $attach->filetype; ?></li>
<?php if(strstr($attach->filetype, "image")) { ?>
	<li>在別的地方使用本圖的 <abbr title="Hypertext Markup Language">HTML</abbr> 語法：<br/>
     <textarea cols="70" style="font-size: 9pt; width: 90%; margin: 3px;"><?php $attach->image_html() ?></textarea> 
<?php } else { ?>
	<li>在別的地方使用這個檔案的 <abbr title="Hypertext Markup Language">HTML</abbr> 語法：<br/>
     <textarea cols="70" style="font-size: 9pt; width: 90%; margin: 3px;"><?php $attach->link_html() ?></textarea> 
<?php } ?>
	</li>
	   </ul>
	   </div>
	   <?
	 } else {
	    echo "<h2>對不起，沒有符合這個 ID 的圖片。</h2>";
	 }
      } else {
	 echo "<h2>請指定要編輯的圖片 ID</h2>";
      }
   } else {
      $id = $_POST['id'];
      $tagline = $_POST['tagline'];
      $display = $_POST['display'];
      $query = "UPDATE $vjdb->attaches SET TAGLINE ='$tagline', DISPLAY = '$display'  WHERE ID='$id'";
      $vjdb->query($query);
      $refer = $_POST['myreferer'];
      if($ajax)  {
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
