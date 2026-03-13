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
} ?>
<h3>附件管理<a name="part-3"></a></h3>
<p>您已經上傳的附件如下，
    <?php
   $uploadpath = $config['attach_path'];
   if (is_dir($uploadpath) && is_writable($uploadpath)) {
      echo "您也可以：<a id=\"upload-attach\" href=\"attach.php?postid=$id\">上傳屬於這篇文章的附件</a>。</p>";
   } else {
      echo "而現在因為您的附件上傳目錄無法寫入，無法上傳附件。</p>";
   }
   ?>
<form action="">
    <?php attach_table($id); ?>
    <input type="hidden" name="postid" value="<?php echo $id ?>" />
</form>