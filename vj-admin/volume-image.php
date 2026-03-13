<?php
if ($_GET['ajax']) {
   include("admin.php");
   header("Content-type: text/html; charset=utf-8");
   $id = $_GET['id'];
}

function image($id)
{
   global $vjdb, $upload, $config;
   $uploadurl = vjinfo('url') . $upload;
   $query = "SELECT * FROM $vjdb->images WHERE VOLUMEID='$id' LIMIT 1";
   $image = $vjdb->get_row($query, ARRAY_A);
   $uploadpath = $config['image_path'];
   if (empty($image)) {
      echo "<p>本期還沒有主題圖片。";
      if (is_dir($uploadpath) && is_writable($uploadpath)) {
         echo "您可以<a id=\"upload_img\" href=\"upload.php?volumeid=$id\">上傳屬於這期期刊的主題圖片</a>。</p>";
      } else {
         echo "因為您的圖片上傳目錄無法寫入，您現在無法上傳本期期刊的主題圖片。</p>";
      }
      return;
   }
   echo "<p>您已經上傳了這一期的主題圖片，";
   if (is_dir($uploadpath) && is_writable($uploadpath)) {
      echo "您可以修改圖說或刪除檔案，如果您想要更換主題圖片，請刪除現有的主題圖片。</p>";
   } else {
      echo "您可以修改本期主題圖片的圖說，或刪除主題圖片。不過，因為您的圖片上傳目錄無法寫入，所以也無法上傳新的主題圖片，替代目前的主題圖片。</p>";
   }
   $myimage = new image($image);
   $myimage->image_edit_html();
   echo '<br clear="all" />';
   return;
}
?>
<h3>當期期刊主題圖片</h3>
<?php image($id); ?>
<div class="submit">
    <input type="submit" value="設定完成" id="vol-submit" />