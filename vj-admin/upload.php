<?php
$mytitle = "上傳檔案";
include("admin.php");
admin_header(); ?>

<h2>圖片上傳</h2>
<?php
$volumeid = $_GET['volumeid'];
$postid = $_GET['postid'];
if (empty($volumeid) && empty($postid)) {
   echo "<p>請指定您想要上傳的是給期刊還是給文章用的圖片</p>";
} else if (!empty($volumeid) && !empty($postid)) {
   echo "<p>請指定您想要上傳的是給期刊還是給文章用的圖片</p>";
} else {
   upload_form($volumeid, $postid);
}
?>
<?php admin_footer() ?>