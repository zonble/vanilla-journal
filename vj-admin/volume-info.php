<?php
   include("admin.php");
   $wysiwyg =1;

   function cat_order_menu($catid=0, $sel= 0){
      global $vj;
      echo '<select name="catorder-'.$catid.'" class="catorder" id="catorder-'.$catid.'">';
      foreach($vj->cats as $cat) {
	 $i++;
	 echo '<option value="'.$i.'"';
	 if($sel == $i) {
	    echo ' selected="selected"';
	 }
	 echo '>';
	 echo $i;
	 echo '</option>';
      }
      echo "</select>";
   }

   function update_volinfo($postvar){
      global $vjdb;
      global $vj;
      if(empty($postvar['alias'])) {
	 return "<p>錯誤：請輸入期數！</p>";
      } 
      $id = $postvar['id'];
      $alias = $postvar['alias'];
      $alias_ext = $postvar['alias_ext'];
      $topic = $postvar['topic'];
      $topic_desc = $postvar['topic_desc'];
      $copyright = $postvar['copyright'];
      $published = $postvar['published'];
      $year = (int)$postvar['year'];
      $month = (int)$postvar['month'];
      $day = (int)$postvar['day'];
      $hour = (int)$postvar['hour'];
      $min = (int)$postvar['min'];
      $sec = (int)$postvar['sec'];
      $date = $year."-".$month."-".$day." ".$hour.":".$min.":".$sec;
      $time = date("Y-m-d H:i:s",strtotime($date));
      $cat_desc_array = array();
      foreach($vj->cats as $cat){
	 $cat_key = "cat-".$cat->cat_id;
	 $cat_postkey = "catdesc-".$cat->cat_id;
	 $cat_desc_array[$cat_key] = $postvar[$cat_postkey];
      }
      $cat_desc = serialize($cat_desc_array);

      foreach($vj->cats as $cat){
	 $cat_key = "cat-".$cat->cat_id;
	 $cat_postkey = "catorder-".$cat->cat_id;
	 $cat_order_array[$cat_key] = $postvar[$cat_postkey];
      }
      $cat_order = serialize($cat_order_array);

      $query = "UPDATE $vjdb->volumes SET ALIAS='$alias', ALIAS_EXT='$alias_ext', TOPIC ='$topic', TOPIC_DESC = '$topic_desc', COPYRIGHT = '$copyright', CREATE_DATE = '$time', PUBLISHED = '$published', CAT_DESC='$cat_desc', CAT_ORDER='$cat_order' WHERE ID = '$id';";
      $vjdb->query($query);
      header("Location: volumes.php");
      /*
      if($vjdb->query($query)) {
	 header("Location: volumes.php");
      } else {
	 $str = "<h2>錯誤！</h2>\n";
	 $str .= "<p>在更新時發生資料庫錯誤！</p>";
	 $vjdb->debug();
	 admin_die($str, "修改期數設定時發生錯誤");
      } */
   }

   $action = $_POST['action'];
   if($action =="update")  {
      update_volinfo($_POST);
   }
   $id = $_POST['id'];
   if(empty($id)) $id = $_GET['id'];
   if(empty($id)) {
      $str = "<h2>錯誤！</h2>\n";
      $str .= "<p>請指定您想要修改期刊的代號！</p>";
      admin_die($str, "修改期數設定時發生錯誤");
   } 
   $volinfo = new volume($id);
   if(empty($volinfo->volume_id)) {
      $str = "<h2>錯誤！</h2>\n";
      $str .= "<p>系統中目前沒有您所指定代號的期刊資料！</p>";
      admin_die($str, "修改期數設定時發生錯誤");
   } else {
      $pagetitle ="設定《".vjinfo('title')."》第 ".$volinfo->volume_alias." 期";
      admin_header();
      $id = $volinfo->volume_id;
?>

<div class="wrap">
    <form method="post" action="volume-info.php" name="volform" id="volform" />
    <input type="hidden" name="id" value="<?php echo $id; ?>" />
    <input type="hidden" name="action" value="update" />
    <h2>期數設定：《<?php info('title') ?>》第 <?php echo $volinfo->volume_alias ?> 期 </h2>
    <div class="tool" id="voltool">
        設定工具：
        <a href="#basic" onclick="vj.volume.show_area('vol_basic');return false;">內容基本設定</a> |
        <a href="#extra" onclick="vj.volume.show_area('vol_extra');return false;">單元順序與說明設定</a> |
        <a href="#" onclick="document.volform.submit();">設定完成</a>
        <br />您也可以：
        <a href="post-add.php?volume=<?php echo $id ?>" onclick="return vj.util.exitconfirm();">增加本期內容</a> |
        <a href="volume-edit.php?volume=<?php echo $id ?>" onclick="return vj.util.exitconfirm();">管理本期文章</a> |
        <a href="import.php?volume=<?php echo $id ?>" onclick="return vj.util.exitconfirm();">從 RSS 匯入文章到本期中</a> |
        <a href="../index.php?volume=<?php echo $id?>" id="viewpage">查看本期網頁</a>
    </div>

    <div id="volume_image">
        <?php include("volume-image.php"); ?>
    </div>

</div>

<div id="volume-main">
    <div id="vol_basic">
        <h3>內容基本設定</h3>
        <p>您可以在此設定本期的期數、主題、說明等基本資訊。</p>
        <table>
            <tr>
                <td class="options"><label for="alias">本期期數</label>：</td>
                <td>第 <input type="text" name="alias" id="alias" size="5"
                        value="<?php echo $volinfo->volume_alias; ?>" /> 期
                    （<label for="alias_ext">延伸期數說明</label>：
                    <input type="text" name="alias_ext" id="alias_ext" size="8"
                        value="<?php echo $volinfo->volume_aliasext; ?>" />）
                    <br />
                    <p><small>您除了可以輸入數字之外，也可以輸入其他符號，例如兩期合出一期的時候，可以輸入「8-9」期。延伸期數說明則是這一期的特殊說明，例如「創刊號」、「特別號」等。</small></p>
                </td>
            </tr>
            <tr>
                <td class="options"><label for="topic">本期主題</label>：</td>
                <td><input type="text" name="topic" id="topic" value="<?php echo $volinfo->volume_topic; ?>"
                        size="60" /><br />
                    <p><small>請輸入這一期期刊的主題，例如本期是一期專刊，就輸入專刊的名稱…等。</small></p>
                </td>
            </tr>
            <tr>
                <td class="options"><label for="topic_desc">本期主題延伸說明</label>：<br />
                </td>
                <td><textarea class="mceEditor" name="topic_desc" id="topic_desc" rows="5"
                        cols="80"><?php echo $volinfo->volume_topic_desc; ?></textarea>
                    <p><small>主題的額外說明，例如可以輸入主題文章的引言。</small></p>
                </td>
            </tr>
            <tr>
                <td class="options"><label for="copyright">本期版權聲明</label>：<br />
                    <span style="white-space: normal;">
                        <p><a href="volume-copyright.php?ajax=1"
                                id="import_copyright">引入之前輸入過的版權聲明</a>（如果您之前曾經輸入過某期期刊的版權聲明，您只要按下「引入之前輸入過的版權聲明」，就可以直接引入之前輸入過的資料，而不必慢慢手動鍵入。）
                        </p>
                    </span>
                </td>
                <td>
                    <div id="copyright_wrap"><textarea class="mceEditor" name="copyright" id="copyright" rows="5"
                            cols="80"><?php echo $volinfo->copyright; ?></textarea></div>
                    <p><small>本期的編輯名單、發行人、特別版權聲明等。</small></p>
                </td>
            </tr>
            <tr>
                <td class="options"><label for="year">出刊日期</label>：</td>
                <?php
     $date = $volinfo->volume_date;
     $year = date("Y", $date); 
     $month = date("m", $date); 
     $day = date("d", $date); 
     $hour = date("H", $date); 
     $min = date("i", $date); 
     $sec = date("s", $date); 
  ?>
                <td>
                    西元<input type="text" name="year" id="year" size="5" value="<?php echo $year; ?>" />年
                    <select name="month" id="date_month">
                        <?php for($i = 1; $i < 13; $i++){
	   echo "<option value=\"$i\"";
	   if($i == $month) { echo ' selected="selected"';}
	   echo ">$i</option>\n";
	} ?>
                    </select> 月
                    <select name="day" id="date_day">
                        <?php for($i = 1; $i < 32; $i++){
	   echo "<option value=\"$i\"";
	   if($i == $day) { echo ' selected="selected"';}
	   echo ">$i</option>\n";
	} ?>
                    </select> 日
                    <input type="text" name="hour" id="date_hour" value="<?php echo $hour; ?>" size="2" /> 時
                    <input type="text" name="min" id="date_min" value="<?php echo $min; ?>" size="2" /> 分
                    <input type="text" name="sec" id="date_sec" value="<?php echo $sec; ?>" size="2" /> 秒
                    <p><small>請輸入這一期期刊是什麼時候出版發行，或是預計什麼時候發行。</small></p>
                </td>
            </tr>
            <tr>
                <td class="options">是否已經上線：</td>
                <td>
                    <?php $published = $volinfo->volume_published; ?>
                    <input type="radio" name="published" id="pub1" value="1"
                        <?php if(!(empty($published))) {echo 'checked="checked"';} ?> /><label for="pub1">是、已經上線</label>
                    <input type="radio" name="published" id="pub2" value="0"
                        <?php if(empty($published)) {echo 'checked="checked"';}  ?> /><label for="pub2">否、尚未上線</label>
                    <p><small>如果您還沒有把所有文章都傳上網路的話，請先選擇「否」，那麼文章就不會顯示在網路上。</small></p>
                    <p><small>但如果已經完成，記得來這裡，勾選成「是」。</small></p>
                </td>
            </tr>
        </table>
    </div>
    <div id="vol_extra">
        <h3>文章單元額外設定</h3>
        <p>您可以在此設定本期各文章單元應該在版面上出現的順序，除了用下拉選單調整外，也可以使用直接拖拉的方式，改變順序（不過，某些瀏覽器目前尚不支援拖拉功能），您也可以針對本期某單元文章加以特別說明。修改完之後請記得按一下「設定完成」。
        </p>
        <?php
   if($vj->cats){
      $i = 0;
      foreach($vj->cats as $cat){
	 $i++;
	 echo '<div id="vol-cat-'.$i.'"><table>';
	 echo "<tr>";
	 echo '<td class="options">';
	 echo '<label for="catdesc-'.$cat->cat_id.'">';
	 echo '「'.$cat->cat_name."」<br />的額外介紹</label>：<br />";
	 echo '順序：';
	 cat_order_menu($cat->cat_id, $cat->cat_vol_order);
	 echo '</td><td>';
	 echo '<textarea cols="80" class="ext" rows="3" id="catdesc-'.$cat->cat_id.'" name="catdesc-'.$cat->cat_id.'">'.$cat->cat_vol_desc.'</textarea></td>';
	 echo "</tr>";
	 echo "</table></div>";
      }
   }
?>
    </div>
    </form>
    <br clear="all" />
</div>
</div>

<?php
   }
   admin_footer();
?>