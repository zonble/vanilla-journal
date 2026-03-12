<?php
include('admin.php');
admin_header('從 RSS 匯入檔案');
// print_r($_POST);
$count = $_POST['count'];
$imported = 0;

function add_post($topic="", $author="", $text="", $cat=1, $volume=1) {
   global $vjdb;
   $msg = "";
   $time = date("Y-m-d H:i:s",time()); 
   if(!$topic) { $msg .= "沒有輸入標題！"; }
   if(!$author) { $msg .= "沒有輸入作者！"; }
   $query = "INSERT INTO $vjdb->post (DISPLAY, POST_ORDER, TOPIC, AUTHOR, BODY, CAT, VOLUME, POST_DATE) VALUES (1, 1, \"$topic\", \"$author\", \"$text\", \"$cat\", \"$volume\", \"$time\")";
   $vjdb->query($query);
   return $msg;
}

for ( $i = 0; $i <= $count; $i++) {
   if($_POST['check-'.$i]) {
      echo "<p>正在匯入第".($i)."篇文章…</p>";
      $topic = $_POST['topic-'.$i];
      $author = $_POST['author-'.$i];
      $text = $_POST['text-'.$i];
      $cat = $_POST['cat-'.$i];
      $volume = $_POST['volume-'.$i];
      $msg = add_post($topic, $author, $text, $cat, $volume);
      if($msg) echo "<p>".$msg."</p>";
      $imported++;
   } 
}


if(!$imported) {
   echo "<h2>沒有可以匯入的文章！</h2>";
   echo "<p>您沒有選擇任何一篇要匯入的文章，因此沒有增加任何內容。</p>";
} else {
   echo "<h2>從 RSS 匯入文章</h2>";
   echo "<p>匯入了 ".$imported." 篇文章。</p>";
   echo "<p>您現在可以：</p>";
   echo "<ul>";
   $volume = $_POST['volume'];
   $volinfo = new volume($volume);
   echo "<li><a href=\"import.php?volume=$volume\" title=\"繼續匯入\">繼續從 RSS 匯入文章到第 ".$volinfo->volume_alias." 期</a></li>";
   echo "<li><a href=\"volume-edit.php?volume=$volume\" title=\"管理文章\">管理第 ".$volinfo->volume_alias." 期的文章</a></li>";
   echo "<li><a href=\"../index.php?volume=$volume\" title=\"查看網頁\">查看第 ".$volinfo->volume_alias." 期的網頁</a></li>";
   echo "</ul>";

}
admin_footer();
?>
