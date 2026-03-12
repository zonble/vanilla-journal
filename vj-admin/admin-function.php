<?php
   /* 讀入網頁 HTML 共用部份 */

   function admin_header($title="") {
      global $vjdb, $wysiwyg, $loginpage, $pagetitle, $ajax;
      if($ajax) {
	 require_once("ajax-header.php");
	 return;
      }
      if($title) {
	 $pagetitle = $title;
      }
      require_once("admin-header.php");
   }

   function admin_footer() {
      global $ajax, $config;
      if($ajax) {
	 require_once("ajax-footer.php");
	 die();
      }
      require_once("admin-footer.php");
      die();
   }

   function admin_die($str = "", $title="") {
      admin_header($title);
      echo '<div class="wrap">';
      echo $str."\n";
      echo '</div>';
      admin_footer();
   }

   function admin_error($msg){
      $str = "<h2>錯誤！</h2>";
      $str .= "<p>$msg</p>";
      admin_die($str. "錯誤！");
   }

   function importance($importance) {
      for($i=0; $i <= $importance; $i++) {
	 $str .= "★";
      }
      return $str;
   }  

   /* 期（volume）相關 function */

   function volume_publish($id, $published){
      global $_SERVER;
      $refer = $_SERVER['REQUEST_URI']."#anchor-".$id;
      $submit = array();
      $submit[0] = "上線";
      $submit[1] = "下線";
      $text = array();
      $text[0] = "<span style=\"color: #900;\">尚未上線</span>";
      $text[1] = "已經上線";
      if($published == 1) {
	 $topublish = 0;
      } else {
	 $topublish = 1;
      }
      echo '<a name="anchor-'.$id.'"></a>';
      echo '<form method="post" action="volume-publish.php">';
      echo $text[$published]."&nbsp;";
      echo '<input type="hidden" name="topublish" value="'.$topublish.'" />';
      echo '<input type="hidden" name="id" value="'.$id.'" />';
      echo '<input type="hidden" name="refer" value="'.$refer.'" />';
      echo '<input type="hidden" name="action" value="publish" />';
      echo '<input type="submit" name="submit" value="'.$submit[$published].'" />';
      echo '</form>';
   }

   function volume_table(){
      global $vjdb;
      $query = "SELECT * FROM $vjdb->volumes ORDER BY CREATE_DATE DESC";
      $volumes = $vjdb->get_results($query, ARRAY_A);
      if($volumes){
	 echo "<table class=\"list_table\">\n";
	 echo "<tr><th>ID</th><th>期數</th><th>本期主題</th><th>出刊日期</th><th>是否已上線</th><th colspan=\"6\">管理功能</th></tr>\n";
	 foreach($volumes as $volume){
	    echo "<tr>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\">".$volume['ID']."</td>";
	    echo "<td>第 ".$volume['ALIAS']." 期</td>";
	    if($volume['TOPIC']) {
	       echo "<td>".$volume['TOPIC']."</td>";
	    } else {
	       echo "<td>&nbsp;</td>";
	    }
	    $date = mysql2date("Y-m-d", $volume['CREATE_DATE']);
	    echo "<td>".$date."</td>";
	    echo "<td>";
	    volume_publish($volume['ID'], $volume['PUBLISHED']);
	    echo "</td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"volume-info.php?id=".$volume['ID']."\" title=\"設定這一期期刊的期數、說明，上傳主題照片\">設定資料</a></td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"post-add.php?volume=".$volume['ID']."\" title=\"新增這一期期刊的文章\">新增文章</a></td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"volume-edit.php?volume=".$volume['ID']."\" title=\"編輯、刪除這一期的文章\">管理文章</a></td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"import.php?volume=".$volume['ID']."\" title=\"透過 RSS 將文章匯入到這一期期刊中\">匯入文章</a></td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"../index.php?volume=".$volume['ID']."\" title=\"瀏覽這一期期刊在網頁上呈現的效果\" class=\"viewpage\">查看頁面</a></td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"volume-delete.php?id=".$volume['ID']."\">刪除本期</a></td>";
	    echo "</tr>\n";
	 }
	 echo "</table>\n";
      } else {
	 echo "<p>&nbsp;目前系統中沒有任何期數資料，請新增！</p>";
      }
   }

   /* 單元列表 */

   function cat_table(){
      global $vjdb, $vj;
      $cats = $vj->cats;
      if($cats){
	 echo "<table class=\"list_table\">\n";
	 echo "<tr><th>ID</td><th>單元名稱</th><th>附註</th><th>本單元文章數</th><th colspan=\"2\">管理</th></tr>";
	 foreach($cats as $cat){
	    echo "<tr>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\">".$cat->cat_id."</td>";
	    echo "<td>".$cat->cat_name."</td>";
	    echo "<td>";
	    if($cat->cat_desc) {
	       echo $cat->cat_desc;
	    } else {
	       echo "&nbsp;";
	    }
	    echo "</td>";
	    echo "<td>";
	    $query = "SELECT COUNT(ID) FROM $vjdb->post WHERE CAT='".$cat->cat_id."'";
	    $count = $vjdb->get_var($query);
	    echo $count;
	    echo "</td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"cats.php?action=edit&amp;id=".$cat->cat_id."\">修改</a></td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"cats.php?action=delete&amp;id=".$cat->cat_id."\">刪除</a></td>";
	    echo "</tr>\n";
	 }
	 echo "</table>";
      } else {
	 echo "<p>&nbsp;目前系統中沒有任何單元，請新增！</p>";
      }
   }

   /* 文章列表 */

   function post_order($order, $postid) {
      if(!$postid) return "";
      $str = "<select name=\"order-".$postid."\">";
      for($i = 0; $i < 20; $i++) {
	 if(($i + 1) == $order) {
	    $selected = 'selected="selected"';
	 } else {
	    $selected = '';
	 }
	 $str .= "<option value=\"".($i + 1)."\" ".$selected.">".($i + 1)."</option>";
      }
      $str .= "</select>";
      return $str;
   }

   function post_table($volume){
      global $vjdb;
      $query = "SELECT ID, TOPIC, AUTHOR, CAT, POST_ORDER, DISPLAY, IMPORTANCE FROM $vjdb->post WHERE VOLUME ='$volume' ORDER BY CAT ASC, POST_ORDER ASC;";
      $items = $vjdb->get_results($query, ARRAY_A);
      $j= 0;
      if($items){
	 echo "<table class=\"list_table\" id=\"post_table\">";
	 echo "<tr><th>ID</th><th>作者</th><th>標題</th>";
	 echo "<th>重要性</th>";
	 echo "<th>文章順序</th><th>是否公開</th><th colspan=\"3\">管理</th></tr>";
	 $i = 1;
	 foreach($items as $item){
	    $cat = $item['CAT'];
	    $j++;
	    if($j == 1) {
	       echo "<tr><td colspan=\"9\"><strong>".cat_name($cat)."</strong></td></tr>\n";
	       echo "\n<tbody id=\"post_table-1\" class=\"post_table\">\n";
	    }

	    if($cat != $prvcat) {
	       if($i > 1 ) {
		  echo "</tbody>";
		  echo "<tr><td colspan=\"9\"><strong>".cat_name($cat)."</strong></td></tr>\n";
		  echo "\n<tbody id=\"post_table-".$i."\" class=\"post_table\">\n";
	       }
	       $i++;
	    }
	    $prvcat = $cat;
	    echo "<tr>";
	    echo "<td class=\"handle\">".$item['ID']."</td>";
	    echo "<td class=\"handle\" style=\"text-align: center; width: 6em;\">";
	    if($item['AUTHOR']) {
	       echo $item['AUTHOR'];
	    } else {
	       echo "&nbsp;";
	    }
	    echo "</td>";
	    echo "<td class=\"handle\">".$item['TOPIC']."</td>";
	    //echo "<td style=\"text-align: center; white-space: nowrap;\">".cat_name($cat)."</td>";
	    /* if($item['POST_ORDER']) {
	       echo "<td style=\"text-align: center; white-space: nowrap;\">".$item['POST_ORDER']."</td>";
	    } else {
	       echo "<td style=\"text-align: center; white-space: nowrap;\">沒有設定順序</td>";
	    } */
	    echo "<td class=\"handle\" style=\"text-align: center; white-space: nowrap;\">".importance($item['IMPORTANCE'])."</td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\">".post_order($item['POST_ORDER'], $item['ID'])."</td>";
	    if($item['DISPLAY']) {
	       echo "<td class=\"handle\" style=\"text-align: center; white-space: nowrap;\">是</td>";
	    } else {
	       echo "<td class=\"handle\" style=\"text-align: center; white-space: nowrap;\">否</td>";
	    }
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"post-edit.php?id=".$item['ID']."\">編輯</a></td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"../index.php?id=".$item['ID']."\" class=\"viewpage\">查看</a></td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"post-delete.php?id=".$item['ID']."\">刪除</a></td>";
	    echo "</tr>\n";
	 }
	 echo "</tbody>\n";
	 echo "</table>\n";
      } else {
	 echo "<p>&nbsp;目前這一期期刊中沒有任何文章，請新增！</p>";
      }
   }

   /* 訂戶列表 */
   
   function subscribe_table($opt = 0, $keyword = ""){
      global $vjdb;
      echo "<form action=\"\">";
      echo "<input type=\"hidden\" name=\"opt\" value=\"".$opt."\" />";
      echo "<input type=\"hidden\" name=\"keyword\" value=\"".$keyword."\" />";
      echo "</form>";
      if($opt == 1 ) {
	 $query = "SELECT * FROM $vjdb->subscribers WHERE VERIFIED=0 ORDER BY EMAIL ASC";
      } else if($opt == 2 && $keyword ) {
	 $query = "SELECT * FROM $vjdb->subscribers WHERE EMAIL LIKE '%$keyword%' OR NAME LIKE '%$keyword%' ORDER BY EMAIL ASC";
      } else {
	 $query = "SELECT * FROM $vjdb->subscribers ORDER BY EMAIL ASC";
      }
      $users = $vjdb->get_results($query, ARRAY_A);
      if($users){
	 echo "<table class=\"list_table\">\n";
	 echo "<tr><th>ID</th><th>電子郵件信箱</th><th>姓名</th><th>是否已經通過認證</th><th colspan=\"3\">管理</th></tr>\n";
	 foreach($users as $user){
	    echo "<tr>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\">".$user['ID']."</td>";
	    if($user['EMAIL']) {
	       echo "<td>".$user['EMAIL']."</td>";
	    } else {
	       echo "<td>&nbsp;</td>";
	    }
	    if($user['NAME']) {
	       echo "<td>".$user['NAME']."</td>";
	    } else {
	       echo "<td>&nbsp;</td>";
	    }
	    if($user['VERIFIED']) {
	       echo "<td style=\"text-align: center;\">是</td>";
	    } else {
	       echo "<td style=\"text-align: center;color: red;\">否</td>";
	    }
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"subscriber-del.php?id=".$user['ID']."\">刪除訂戶</a></td>";
	    echo "<td style=\"text-align: center; white-space: nowrap;\"><a href=\"subscriber-info.php?id=".$user['ID']."\">設定訂戶資料</a></td>";
	    echo "</tr>\n";
	 }
	 echo "</table>\n";
      } else {
	 if($opt) {
	    echo "<p>&nbsp;目前系統中沒有符合條件的訂戶！</p>";
	 } else {
	    echo "<p>&nbsp;目前系統中沒有任何訂戶，請新增！</p>";
	 }
      }
   }

   function attach_table($id) {
      global $vjdb;
      $query = "SELECT * FROM $vjdb->attaches WHERE POSTID='$id'";
      $result = $vjdb->get_results($query, ARRAY_A);
      if(empty($result)) return;
      echo "<table class=\"list_table\" id=\"attach_table\">";
      echo "<tr><th>檔案名稱</th><th>檔案說明</th><th>是否顯示</th><th>檔案類型</th><th colspan=\"2\">管理</th></tr>";
      foreach($result as $array){
         $attach = new attach();
         $attach->get_attachinfo($array);
         $attach->attach_table_row();
      }
      echo '</table>';
   }

   /* 清除不要的標籤 */

   function removeEvilStyles($tagSource)
   {
      $evilStyles = array('font', 'font-family', 'font-face', 'font-size', 'font-size-adjust', 'font-stretch', 'font-variant');

      $find = array();
      $replace = array();

      foreach ($evilStyles as $v)
      {
	 $find[]    = "/$v:.*?;/";
	 $replace[] = '';
      }

      return preg_replace($find, $replace, $tagSource);
   }

   function cleantag($source)
   {
      $allowedTags = '<h1><h2><h3><h4><h5><a><img><label>'.
	 '<p><br><span><sup><sub><ul><li><ol>'.
	 '<table><tr><td><th><tbody>'.
	 '<hr><em><strong><b><i>';
      $source = strip_tags(stripslashes($source), $allowedTags);
      $source = preg_replace('/\s\s+/', ' ', $source);
      return trim(preg_replace('/<(.*?)>/ie', "'<'.removeEvilStyles('\\1').'>'", $source));
   }

   function sel_vol($name, $volume) {
      global $vj;
      if(!$vj->volumes) {
	 $vj->query_all_volumes();
      }
      echo '<select name="'.$name.'" id="'.$name.'" class="sel_vol">';
      foreach($vj->volumes as $item) {
	 echo "<option value=\"".$item['ID']."\"";
	 if($item['ID'] == $volume) echo ' selected="selected"';
	 echo ">第 ".$item['ALIAS']." 期";
	 if($item['ID'] == $volume) echo ' *';
	 echo "</option>";
      }

      echo '</select>';
   }

   function sel_cat($name, $cat) {
      global $vj;
      echo '<select name="'.$name.'" id="'.$name.'" class="sel_cat">';
      foreach($vj->cats as $mycat){
	 echo "<option value=\"".$mycat->cat_id."\"";
	 if($mycat->cat_id == $cat) echo ' selected="selected"';
	 echo ">".$mycat->cat_name;
	 if($mycat->cat_id == $cat) echo ' *';
	 echo "</option>";
      } 
      echo '</select>';
   }

   function sel_import() {
      global $vjdb;
      $query = "SELECT * FROM $vjdb->feeds ORDER BY TITLE ASC, ID ASC";
      $results = $vjdb->get_results($query, ARRAY_A);
      if($results) {
	 echo "<select id=\"import_sel\" name=\"sel_id\">";
	 echo "<option value=\"\">請選擇…</option>";
	 foreach($results as $result) {
	    echo "<option value=\"".$result['ID']."\">";
	    echo $result['TITLE']."（".$result['URL']."）";
	    echo "</option>";
	 }
	 echo "</select>";
      } else {
	 echo "您還沒有將任何 RSS 資料來源紀錄到您的系統中！";
      }
   }

   function update_key($key = "", $value = "") {
      global $vjdb;

      if(!$key) return;
      if(!$value) return;
      $query = "SELECT ID FROM $vjdb->infos WHERE `KEY` = '$key';";
      $infoid = $vjdb->get_var($query);
      if(!$infoid) {
	 $query = "INSERT INTO $vjdb->infos (`VALUE`, `KEY`) VALUES ('$value', '$key');";
      } else {
	 $query = "UPDATE $vjdb->infos SET `VALUE`='$value' WHERE `KEY` = '$key';";
      }
      $vjdb->query($query);
   }
?>
