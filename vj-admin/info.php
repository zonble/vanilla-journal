<?php
include("admin.php");
$wysiwyg = 1;

$name = array(
   "title" => "期刊名稱",
   "description" => "期刊簡述",
   "url" => "網址設定",
   "publisher" => "期刊出版單位",
   "place" => "期刊出版地",
   "sender" => "電子報寄件人電子郵件信箱",
   "sender_name" => "電子報寄件人名稱",
   "isalbum" => "插入幾篇圖片以上，視為相本",
   "thumb_max" => "縮圖的尺寸上限（pixel）",
   "image_max" => "圖片的尺寸上限（pixel）",
   "image_path" => "圖片上傳相對路徑",
   "attach_path" => "附件上傳相對路徑",
);

$submit = $_POST['submit'];
if ($submit) {
   foreach ($_POST as $key => $value) {
      if ($key == "submit")
         continue;
      if (empty($value)) {
         $str .= "<p>「";
         if ($name[$key]) {
            $str .= $name[$key];
         } else {
            $str .= $key;
         }
         $str .= "」不得為空</p>";
      } else {
         update_key($key, $value);
      }
   }
   if (empty($str)) {
      $str = "<div id=\"msg\"><strong>資料已更新！</strong></div>";
   }
}

$vj->query_info();
$info = $vj->info;

function keyrow($key)
{
   global $info, $name;
   echo '<tr><td class="options"><label for="info-' . $key . '">';
   if ($name[$key]) {
      echo $name[$key];
   } else {
      echo $key;
   }
   echo '：</label></td><td>';
   echo '<input type="text" name="' . $key . '" id="info-' . $key . '" value="' . $info[$key] . '" size="50" /></td></tr>';
}
admin_header("期刊系統選項設定");
?>
<h2>期刊系統選項設定</h2>
<?php
if ($submit) {
   echo $str;
}
?>
<div id="infotool" class="tool">
    各項設定：
    <a href="#basic" onclick="vj.info.show_area('info_basic'); return false;">基本設定</a> |
    <a href="#images" onclick="vj.info.show_area('info_detail'); return false;">詳細介紹</a> |
    <a href="#attach" onclick="vj.info.show_area('info_upload'); return false;">圖片、附件上傳相關設定</a>
</div>

<p>您的期刊資料如下。請在設定完畢之後，按下「修改系統基本設定」按鈕。</p>
<form method="post" action="info.php" name="infoform" id="infoform">
    <div id="info_basic">
        <h3>基本設定</h3>
        <p>您可以在此設定期刊的名稱、出版單位、地點等基本資訊。</p>
        <table>
            <?php
         keyrow('title');
         keyrow('description');
         keyrow('publisher');
         keyrow('place');
         keyrow('sender');
         keyrow('sender_name');
         ?>
        </table>
    </div>
    <div id="info_detail">
        <h3>詳細介紹</h3>
        <table>
            <tr>
                <td class="options"><label for="info-credit">版權資訊</label>：</td>
                <td>
                    <textarea class="mceEditor" name="credit" id="info-credit"
                        rows="5"><?php echo $info['credit']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="options"><label for="info-about">期刊簡介</label>：</td>
                <td>
                    <textarea class="mceEditor" name="about" id="info-about"
                        rows="5"><?php echo $info['about']; ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    <div id="info_upload">
        <h3>圖片、附件上傳相關設定</h3>
        <table>
            <?php
         keyrow('isalbum');
         keyrow('image_max');
         keyrow('thumb_max');
         keyrow('image_path');
         keyrow('attach_path');
         ?>
        </table>
    </div>
    <input type="submit" name="submit" value="修改系統基本設定 »" />
</form>

<?php
admin_footer();
?>