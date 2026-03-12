<?php
   include("admin.php");
   $query = "SELECT ID, CREATE_DATE, ALIAS, PUBLISHED FROM $vjdb->volumes ORDER BY CREATE_DATE DESC";
   $results = $vjdb->get_results($query, ARRAY_A);
   if(empty($results)) {
      $str = "<h2>您還不可以發送期刊</h2>";
      $str .= "<p>對不起，您還沒有設定任何一期的期刊，還不能用這個功能！</p>";
      admin_die($str ,"無法執行寄送作業");
   }
   admin_header("執行寄送作業");
   $myvolume = $_GET['volume'];
?>

<h2>發送期刊</h2>
<form method="post" action="mail-send.php">
    <fieldset>
        <legend>請選擇您想要發送的期刊：</legend>
        <p>
            <select name="volume" id="volume">
                <?php
	foreach($results as $volume) {
	   echo "<option value=\"".$volume['ID']."\"";
	   if($myvolume == $volume['ID']) {
	      echo ' selected="selected"';
	   }
	   echo ">第 ".$volume['ALIAS']." 期";
	   $date = mysql2date("Y-m-d", $volume['CREATE_DATE']);
	   echo " (".$date." 發行)";
	   if(empty($volume['PUBLISHED'])) {
	      echo " - 尚未上線";
	   }
	   echo "</option>\n";
	}
?>
            </select>
            <script type="text/javascript">
            <!--
            function visit_volume() {
                var volume = $('volume').value;
                var url =
                    '<?php echo vjinfo('url')."read.php?url=".urlencode(vjinfo('url')."index.php?is_email=1&volume="); ?>' +
                    volume;
                if (vj.block.show) {
                    vj.block.show(url);
                } else {
                    window.open(url, '_blank', 'width=800,height=600,resizable=1');
                }
            }
            //
            -->
            </script>
            <a href="#" onclick="visit_volume(); return false;">預覽這期的期刊</a>
        </p>
        <p><small>請您再選好要送出的期刊後，先按下「預覽這期的期刊」，檢查是否正確無誤。如果無法正確讀取，在寄送時同樣會有問題。</small></p>
    </fieldset>
    <fieldset>
        <legend>請問您打算先寄給自己測試看看，還是打算直接寄出？</legend>
        <p><input type="radio" name="action" id="action-1" value="test" checked="checked" />
            <label for="action-1">寄給自己一封測試看看</label>
            <input type="radio" name="action" id="action-2" value="all" />
            <label for="action-2">寄送至訂戶，正式發行</label>
        </p>
        <p><small>您最好是先寄到自己的信箱測試一下，然後才寄出去</small></p>
    </fieldset>
    <fieldset>
        <legend>如果您只是打算先寄給自己看看，請輸入您想要發送期刊的測試信箱：</legend>
        <p>電子郵件信箱：<input name="email" id="email" value="<?php info('sender'); ?>" /></p>
    </fieldset>
    <input type="submit" id="submit" name="submit" value="寄出期刊" />
</form>

<?php admin_footer(); ?>