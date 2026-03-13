<?php
if ($_GET['ajax']) {
   include("admin.php");
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
   header("Cache-Control: no-store, no-cache, must-revalidate");
   header("Cache-Control: post-check=0, pre-check=0", false);
   header("Pragma: no-cache");
   header("Content-type: text/html; charset=utf-8");
   $id = $_GET['id'];
}

function image_list($id)
{
   global $vjdb, $upload;
   $uploadurl = vjinfo('url') . $upload;
   $query = "SELECT * FROM $vjdb->images WHERE POSTID='$id' ORDER BY 'IMAGE_ORDER' ASC ,ID DESC";
   $result = $vjdb->get_results($query, ARRAY_A);
   if (empty($result)) {
      echo "<p>這篇文章目前沒有所屬圖片</p>";
      return;
   }
   foreach ($result as $image) {
      $myimage = new image($image);
      $myimage->image_edit_html();
   }
   echo '<br clear="all" />';
}

if ($id) {
   ?>
<h3>圖片管理<a name="part-2"></a></h3>
<p>您已經上傳的圖片如下，您可以在此編輯、刪除圖片，
    <?php
      $uploadpath = $config['image_path'];
      if (is_dir($uploadpath) && is_writable($uploadpath)) {
         echo "也可以：<a id=\"upload_img\" href=\"upload.php?postid=$id\">上傳屬於這篇文章的圖片</a>。</p>";
      } else {
         echo "而因為您的圖片上傳目錄無法寫入，您現在無法上傳圖片。</p>";
      }
      ?>
<form id="image_form" method="post" action="upload-order.php">
    <script type="text/javascript">
    document.write("<p>您可以拖拉的方式，調整照片在文章或相本中的排列順序，在調整完之後，請記得按一下「更新圖片順序」。</p>");
    </script>
    <p>
        <input type="submit" name="submit" id="image_form_submit" value="更新圖片順序" />&nbsp;
        <input type="reset" name="reset" id="image_form_reset" value="恢復成上一次儲存的順序" />
    </p>
    <div id="image_block">
        <?php image_list($id); ?>
        <br clear="all" />
    </div>
    <input type="hidden" name="postid" value="<?php echo $id ?>" />
</form>
<?php } ?>