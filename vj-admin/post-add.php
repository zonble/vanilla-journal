<?php
include("admin.php");
$action = $_POST['action'];
if ($action == "insert") {
   foreach ($_POST as $key => $value) {
      if ($key == 'action')
         continue;
      $$key = $value; //字串直接轉成變數名稱 XD
      $keystr .= strtoupper($key) . ",";
      $values .= "'" . $value . "',";
   }
   $time = date("Y-m-d H:i:s", time()); //反正不用自己輸入發表時間..
   $keystr .= "POST_DATE";
   $values .= "'" . $time . "'";
   $query = "INSERT INTO $vjdb->post (" . $keystr . ") VALUES (" . $values . ")";
   $query = str_replace(",)", ")", $query);
   if (empty($topic)) {
      $err_str = "請輸入標題！";
   }
   if (empty($err_str)) {
      $vjdb->query($query);
      header("Location: volume-edit.php?volume=$volume");
   }
} else {
   $volume = $_GET['volume'];
   $target = "post-add.php";
   $refer = $_SERVER['HTTP_REFERER'];
}
$query = "SELECT ALIAS FROM $vjdb->volumes WHERE ID='$volume' LIMIT 1";
$alias = $vjdb->get_var($query);
$actionlabel = "新增文章！";
$action = "insert";
$wysiwyg = 1;
admin_header("發表新文章");
if ($alias) {
   ?>
<div class="wrap">
    <h2>新增《<?php info('title') ?>》第 <?php echo $alias ?> 期的文章內容</h2>

    <p>注意：您必須要先將這份文件新增到資料庫之後，才可以使用圖片以及附件上傳功能。</p>

    <?php
      if ($err_str) {
         echo "<div id=\"msg\">" . $err_str . "</div>";
      }
      $display = 1;
      $post_status = "add";
      include "post-form.php";
      ?>
    <br clear="all" />
</div>

<?php
}
admin_footer();
?>