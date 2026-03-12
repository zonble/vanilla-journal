<?php 
   include("admin.php");
   error_reporting(0);
   admin_header("編輯 RSS 匯入來源");

   function rss_form($id, $title, $url) {
      global $ajax;

      echo "<h2>編輯 RSS 匯入來源</h2>";
      echo "<p>您可以將這個 RSS 匯入來源修改成您想要的名稱，或是換成另外一個網址。</p>";
      echo "<form method=\"post\">";
      echo "<p><label for=\"rss_title\">匯入來源標題</label>：<input type=\"text\" name=\"title\" id=\"rss_title\" value=\"".$title."\" size=\"30\"/></p>";
      echo "<p><label for=\"rss_url\">匯入來源網址</label>：<input type=\"text\" name=\"url\" id=\"rss_url\" value=\"".$url."\" size=\"50\" /></p>";
      echo "<p><input type=\"submit\" name=\"submit\" value=\"確認送出！\" /></p>";
      echo "<input type=\"hidden\" name=\"action\" value=\"edit\" />";
      echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\" />";
      if($ajax) {
	 echo "<input type=\"hidden\" name=\"ajax\" value=\"1\" />";
      }
      echo "</form>";
   }

   function update_rss($id, $title, $url) {
      global $vjdb;
      $query = "UPDATE $vjdb->feeds SET TITLE ='$title', URL = '$url'  WHERE ID='$id'";
      if($vjdb->query($query)) {
	 echo "<h2>更新完畢</h2>";
	 echo "<p>您已經將 RSS 訂閱來源換成新的資料了。</p>";
      } else {
	 echo "<h2>資料庫內容並沒有更新！</h2>";
	 echo "<p>系統無法用您輸入的資料更新資料庫，可能是因為您輸入的資料與之前一模一樣，也可能是因為資料庫發生錯誤。</p>";
      }
   }

   function load_rss($id) {
      global $vjdb;
      $query = "SELECT * FROM $vjdb->feeds WHERE ID = $id";
      $result = $vjdb->get_row($query, ARRAY_A);
      if(empty($result['ID'])) {
	 echo "<h2>錯誤！</h2>";
	 echo "<p>對不起，系統中沒有這個 RSS 匯入來源的資料！</p>";
	 admin_footer();
      } else {
	 rss_form($result['ID'], $result['TITLE'], $result['URL']);
      }
   }


   if($_POST['submit'] && $_POST['action'] == "edit") {
      define('MAGPIE_DIR', '../vj-include/');
      define('MAGPIE_CACHE_DIR', '../vj-tmp/');
      define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
      require_once(MAGPIE_DIR.'rss_fetch.inc');
      $id = $_POST['id'];
      $url = $_POST['url'];
      $title = $_POST['title'];

      $query = "SELECT * FROM $vjdb->feeds WHERE ID = $id";
      $result = $vjdb->get_row($query, ARRAY_A);

      if($url != $result['URL']) {
	 $rss = fetch_rss($url);
	 if(!$rss) {
    echo "<h2>錯誤！無法從您輸入的網址中找到資料！</h2>";
	    echo "<p>您輸入了新的網址，但是系統無法從您輸入的網址中找到任何資料。</p><p>您或許打錯了網址，也有可能是因為網路問題、或對方主機目前正處於關機中…等原因所造成的。您可以試試看更正您所輸入的網址，然後重新加入。</p>";
	    rss_form($id, $title, $url);
	 }
      } else if (empty($url)) {
	 echo "<h2>錯誤！</h2>";
	 echo "<p>請輸入網址！</p>";
	 rss_form($id, $title, $url);
      } else if (empty($title)) {
	 echo "<h2>錯誤！</h2>";
	 echo "<p>請輸入 RSS 匯入來源的標題！</p>";
	 rss_form($id, $title, $url);
      } else {
	 update_rss($id, $title, $url);
      }
   } else if($_GET['id']) {
      load_rss($_GET['id']);
   } else {
      $query = "SELECT * FROM $vjdb->feeds ORDER BY TITLE ASC, ID ASC";
      $results = $vjdb->get_results($query, ARRAY_A);

      if($results) {
	 echo "<h2>編輯 RSS 匯入來源</h2>";
	 echo "<p>請選擇您要編輯的項目。</p>";
	 echo "<ul>";
	 foreach($results as $result) {
	    echo "<li>";
	    echo "<a href=\"import-edit.php?id=".$result['ID']."\">編輯</a>、";
	    echo "<a href=\"import-delete.php?id=".$result['ID']."\">刪除</a>：";
	    echo $result['TITLE'];
	    echo "（".$result['URL']."）</li>";
	 }
	 echo "</ul>";
      }
   }
   admin_footer();

?>
