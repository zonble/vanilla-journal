<?php
   include("admin.php");
   error_reporting(0);

   function import_form($url="", $volume = 0) {
      if(!$url) {
	 $url = "http://";
      }
      if($volume) {
	 $volinfo = new volume($volume);
	 echo '<input type="hidden" name="volume" value="'.$volume.'" />';
	 echo '<p>您正要透過 RSS 將資料匯入到第 '.$volinfo->volume_alias.' 期的期刊中。</p>';
      }
      echo '<p>請輸入您要匯入資料的 RSS 來源，您可以（請選擇）：</p>';
      echo '<form action="import.php" method="post" id="import_form">';
      echo '<p><input type="radio" name="source" id="import_source_1" value="input" checked="checked">&nbsp;';
      echo '<label for="import_url">自行輸入 RSS 網址：</label>';
      echo '<input type="text" name="url" id="import_url" value="'.$url.'" size="50" />&nbsp;';
      echo '（<a href="import-add.php" id="import_add">將這個網址加入資料庫中</a>）';
      echo '</p>';
      echo '<p><input type="radio" name="source" id="import_source_2" value="sel">&nbsp;';
      echo '<label for="import_sel">選擇之前紀錄的 RSS 網址：</label>';
      echo "<span id=\"import_sel_span\">";
      sel_import();
      echo "</span>";
      echo '&nbsp;（<a href="import-edit.php" id="import_edit">編輯這筆紀錄</a> | ';
      echo '<a href="import-delete.php" id="import_delete">刪除這筆紀錄</a>）';
      echo '</p><p>選擇完畢後，請點選 <input type="submit" name="submit" value="從 RSS 匯入文章 »" /></p>';
      echo '</form>';
   }

   function get_url($id = 0) {
      global $vjdb;
      if($id) {
	 $query = "SELECT URL FROM $vjdb->feeds WHERE ID = $id";
	 $url = $vjdb->get_var($query);
	 return $url;
      }
   }

   define('MAGPIE_DIR', '../vj-include/');
   define('MAGPIE_CACHE_DIR', '../vj-tmp/');
   define('MAGPIE_OUTPUT_ENCODING', 'UTF-8'); 
   require_once(MAGPIE_DIR.'rss_fetch.inc');

   $source = $_POST['source'];
   if($source == "sel") {
      $sel_id = $_POST['sel_id'];
      if($sel_id) {
	 $url = get_url($sel_id);
      }
   } else {
      $url = $_POST['url'];
   }

   if ( $url ) {
      $rss = fetch_rss($url);
      // print_r($rss);
      if(!$rss) {
	 admin_header("RSS 匯入時發生錯誤");
	 echo "<h2>從 <abbr title=\"Really Simple Syndication\">RSS</abbr> 匯入文章</h2>\n";
	 echo "<p><strong>您要匯入的 RSS 網址為</strong>：".$url."</p>";
	 echo "<p>對不起！無法從您所輸入的網址中載入資料！請檢查！";
	 import_form($url);
	 admin_footer();
      } else {
	 $volume = $_POST['volume'];
	 $wysiwyg = 1;
	 admin_header("修改從 RSS 匯入的資料");
	 echo "<h2>從 <abbr title=\"Really Simple Syndication\">RSS</abbr> 匯入文章</h2>\n";
	 echo "<p><strong>您要匯入的 RSS 網址為</strong>：".$url."</p>\n";
	 echo "<p><strong>對方網站名稱</strong>：" . $rss->channel['title'] . "</p>\n";
	 echo "<p>您所選擇匯入的 RSS 檔案的內容如下，請選擇您要匯入的項目，或修改文章的標題、內文；在修改完畢後，請按下「匯入選擇的文章」按鈕。注意：在完成匯入內文之後，您可以視需要，上傳各篇文章的所屬圖片與附件。</p>\n";
	 // print_r($rss);
	 if($rss->items) {
	    echo "<form action=\"import-submit.php\" method=\"post\">";
	    echo "<input type=\"hidden\" name=\"count\" value=\"".count($rss->items)."\">";
	    echo "<table id=\"import_table\" class=\"list_table\">";
	    echo "<tr><th>編號</th><th colspan=\"2\">內容</th></tr>";
	    foreach ($rss->items as $item) {
	       $i++;
	       echo '<tr>';
	       echo '<td style="text-align: center; width: 3em; white-space: nowrap;">'.$i.'</td>';
	       echo '<td style="width: 20em; white-space: nowrap; vertical-align: top;">';
	       echo '<p><input name="check-'.$i.'" type="checkbox" checked="checked" class="rss_check"/><label for="check-'.$i.'">匯入這篇文章</label></p>';
	       echo '<p><label for="topic-'.$i.'">標題</label>：<input type="text" name="topic-'.$i.'" value="'.$item['title'].'" size="30" class="rss-topic" />';
	       echo '<br /><label for="author-'.$i.'">作者</label>：<input type="text" name="author-'.$i.'" value="'.$item['author'].'" size="30" class="rss-author" />';
	       echo '<br /><label for="cat-'.$i.'">文章單元</label>：';
	       sel_cat('cat-'.$i, 0);
	       echo '<br /><label for="volume-'.$i.'">匯入到</label>：';
	       sel_vol('volume-'.$i, $volume);
	       echo '</p></td>';
	       if($item['atom_content']) {
		  $text = $item['atom_content'];
	       } else if($item['content']) {
		  $text = $item['content'];
	       } else {
		  $text = $item['summary'];
	       }

	       echo '<td style="vertical-align: top;"><p><label for="text-',$i,'">內文</label>：</p><textarea class="mceEditor" name="text-'.$i.'">'.$text.'</textarea></td>';
	       echo '</tr>';

	    }
	    echo "</table>";
	    echo "<p>調整全部文章：";
	    echo "<input type=\"button\" id=\"sel-all\" value=\"選擇全部\"/>";
	    echo "<input type=\"button\" id=\"sel-none\" value=\"全部不選\"/>";
	    echo "，將所有的文章的單元調整為 ";
	    sel_cat('cat-all', 0);
	    echo " ，匯入到 ";
	    sel_vol('volume', $volume);
	    echo " 。</p>";
	    echo "<p><input type=\"submit\" name=\"submit\" value=\"匯入選擇的文章 »\" /></p>";

	    echo "</form>";
	 } else {
	    echo "<p>在 RSS 中沒有可以匯入的文章！</p>";
	 }
      }
   } else {
      admin_header("從 RSS 匯入文章");
      $volume = $_GET['volume'];
      echo '<div class="wrap">';
      echo '<h2>從 RSS 匯入文章</h2>';
      import_form("", $volume);
      echo '</div>';
      echo '<div class="wrap">';
      echo '<p>說明：您可以透過其他網站所提供的 RSS 訂閱檔案，將其他網站上已經發佈的文章，直接快速匯入到期刊裡。</p>';
      echo '</div>';
   }
   admin_footer();
?>
