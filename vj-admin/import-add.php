<?php   
   include("admin.php");
   error_reporting(0);

   function add_form($url="") {
      global $ajax;
      echo '<form action="import-add.php" method="post">';
      echo '<p>';
      echo '<label for="url">網址：</label>';
      if(!$url) {
	 $url = "http://";
      }
      echo '<input type="text" name="url" value="'.$url.'" size="50" />';
      echo '<input type="submit" name="submit" value="加入 »" />';
      if($ajax) {
	 echo '<input type="hidden" name="ajax" value="1"/>';
      }
      echo '</p>';
      echo '</form>';
   }
   define('MAGPIE_DIR', '../vj-include/');
   define('MAGPIE_CACHE_DIR', '../vj-tmp/');
   define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
   require_once(MAGPIE_DIR.'rss_fetch.inc');

   admin_header("將 RSS 資料來源加入到資料庫中");

   if($_POST['url']) {
      $url = $_POST['url'];
      $rss = fetch_rss($url);
      $id = $vjdb->get_var("SELECT ID FROM $vjdb->feeds WHERE URL='$url' LIMIT 1");
      if(!$rss) {
	 echo "<h2>錯誤！無法從您輸入的網址中找到資料！</h2>";
	 echo "<p>系統無法從您輸入的網址中找到任何資料，您或許打錯了網址，也有可能是因為網路問題、或對方主機目前正處於關機中…等原因所造成的。您可以試試看更正您所輸入的網址，然後重新加入。</p>";
	 add_form($url);
      } else if($id) {
	 echo "<h2>錯誤！請不要重複輸入！</h2>";
	 echo "<p>系統中已經有了這個 RSS 資料來源了！（您所輸入的網址為：".$url."）</p>";
      } else {
	 $title = $rss->channel[title];
	 echo "<h2>成功加入 RSS 資料來源！</h2>";
	 echo "<p>您已經將這筆資料成功加入到了資料庫中，這筆資料的名稱為：".$title."。</p>";
	 $query = "INSERT INTO $vjdb->feeds (URL, TITLE) VALUES ('$url', '$title')";
	 $vjdb->query($query);
      }

   } else {
      $url = $_GET['url'];
?>
<div class="wrap">
    <h2>將 RSS 資料來源加入到資料庫中</h2>
    <p>請輸入您想要加入到資料庫中的 RSS 資料來源的網址</p>
    <?php add_form($url) ?>
</div>
<?php
   }
   admin_footer();
?>