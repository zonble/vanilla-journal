<?php
include("admin.php");

if (!function_exists('mime_content_type')) {
   function mime_content_type($f)
   {
      $str = exec(trim('file -bi ' . escapeshellarg($f)));
      $array = explode(";", $str);
      $str = $array[0];
      return $str;
   }
}

if (!function_exists('file_put_contents')) {
   define('FILE_APPEND', 1);
   function file_put_contents($n, $d, $flag = false)
   {
      $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
      $f = @fopen($n, $mode);
      if ($f == false) {
         return 0;
      } else {
         if (is_array($d))
            $d = implode($d);
         $bytes_written = fwrite($f, $d);
         fclose($f);
         return $bytes_written;
      }
   }
}

function change_theme($theme = "")
{
   global $vj;
   global $msg;

   if (!$_POST['theme'])
      return;
   update_key("theme", $_POST['theme']);
   $vj->query_info();
   $msg = "您的佈景主題已經換成 <strong>" . $_POST['theme'] . "</strong> 了！";
}

if ($_POST['submit'] && $_POST['action'] == "theme") {
   change_theme($_POST['theme']);
}
admin_header("編輯網頁範本");
?>

<?php
if ($_POST['submit'] && $_POST['action'] == "edit") {
   $page = $_POST['page'];
   $content = $_POST['content'];
   $filename = $config['theme_path'] . $page;
   @copy($filename, $filename . ".bak");
   $content = str_replace('\"', '"', $content);
   $content = str_replace("\'", "'", $content);
   @file_put_contents($filename, $content);
   echo "<h2>檔案已儲存</h2>";
   echo "<p>您所做的修改已經存入檔案中了！</p>";
   if (file_exists($filename . ".bak")) {
      echo "<p>如果您發現您這次的修改有問題，可以<a href=\"file-edit.php?page=" . $page . ".bak\">開啟前一版的備份檔案</a>，複製之前的內容。</p>";
   }
} else if ($_GET['page']) {
   $page = $_GET['page'];
   $filename = $config['theme_path'] . $page;
   $content = @file_get_contents($filename);
   $contenttype = mime_content_type($filename);
   if (!file_exists($filename)) {
      echo "<h2>檔案不存在！</h2>";
      echo "<p>您所指定的檔案不存在！</p>";
   } else if (!strstr($contenttype, "text")) {
      echo "<h2>檔案類型錯誤！</h2>";
      echo "<p>您所指定的檔案不是文字檔案格式，無法編輯！</p>";
   } else {
      ?>
<h2>編輯網頁範本檔案</h2>
<form action="file-edit.php" method="post">
    <p><strong>檔案名稱</strong>： <?php echo $page ?> 、<strong>檔案類型</strong>：<?php echo mime_content_type($filename) ?></p>
    <input type="hidden" value="<?php echo $page; ?>" name="page" id="page" />
    <textarea id="content" name="content"
        style="padding: 5px; width: 90%; height: 300px; font-size: 12pt; font-family: mono-space;"><?php echo $content; ?></textarea>
    <br />
    <?php
            if (is_dir($filename)) {
               echo "<p>對不起，您所指定的檔名為目錄，無法編輯！</p>";
            } else if (!is_writable($filename)) {
               echo "<p>對不起，您沒有這個檔案的寫入權限，無法寫入！</p>";
               echo "<p>請將檔案設定為可以寫入，例如在 Linux 等作業系統下，請在命令列下輸入 <code>chmod 666 " . $page . "</code>。</p>";
            } else {
               ?>
    <input type="submit" id="submit" name="submit" value="儲存檔案！" />
    <input type="hidden" name="action" value="edit" />
</form>
<? }
   }
} else {
   ?>

<h2>編輯網頁範本</h2>
<div id="filetool" class="tool">
    您可以：
    <a href="#theme" onclick="return vj.file.show_area('file_theme');">選用佈景主題</a> |
    <a href="#edit" onclick="return vj.file.show_area('file_edit');">編輯網頁範本檔案</a>
</div>
<div id="file_theme" class="wrap">
    <?php if ($msg) {
         echo '<div id="msg">' . $msg . '</div>';
      } ?>

    <a name="theme"></a>
    <h3>選用佈景主題</h3>
    <p>請選擇您想要使用的佈景主題，在選定之後，請按下「選用佈景主題」按鈕。</p>
    <p>目前在系統中可以選用的佈景主題如下：</p>
    <?php
         function parse_about($file = "")
         {
            $about = array();
            $content = file_get_contents($file);
            if (!$content)
               return $about;
            preg_match("/<name>(.+?)<\/name>/", $content, $match);
            $about['name'] = $match[1];
            preg_match("/<author>(.+?)<\/author/", $content, $match);
            $about['author'] = $match[1];
            preg_match("/<description>(.+?)<\/description>/", $content, $match);
            $about['description'] = $match[1];
            preg_match("/<url>(.+?)<\/url>/", $content, $match);
            $about['url'] = $match[1];
            return $about;
         }

         function parse_xml($file = "")
         {
            $about = array();
            $doc = new DOMDocument();
            $doc->load($file);
            $doc->normalize();
            // $about['name'] = $doc->getElementsByTagName('name')->item(0)->nodeValue;
            // $about['author'] = $doc->getElementsByTagName('author')->item(0)->nodeValue;
            // $about['description'] = $doc->getElementsByTagName('description')->item(0)->nodeValue;
            // $about['url'] = $doc->getElementsByTagName('url')->item(0)->nodeValue;
            return $about;
         }

         function show_row($v = "", $nowrap = 0)
         {
            if ($nowrap) {
               echo "<td style=\"white-space: pre;\">";
            } else
               echo "<td>";
            if ($v) {
               echo $v;
            } else {
               echo "&nbsp;";
            }
            echo "</td>";
         }

         function show_info($f)
         {
            $about = array();
            $file = $f . "/about.xml";
            if (file_exists($file)) {
               if (class_exists("DOMDocument")) {
                  $about = parse_xml($file);
               } else {
                  $about = parse_about($file);
               }
            }
            show_row($about['name'], 1);
            show_row($about['author'], 1);
            show_row($about['description']);
         }

         $themes = $config['basepath'] . "theme/";
         $d = opendir($themes);
         echo "<form method=\"post\" action=\"file-edit.php\">";
         echo "<table class=\"list_table\">";
         echo "<tr><th>選用</th><th>佈景主題代稱</th><th>佈景主題名稱</th><th>作者</th><th>說明</th><th>預覽</th></tr>";
         while ($f = readdir($d)) {
            $fullpath = $themes . $f;
            if ($f[0] == ".")
               continue;
            if (is_dir($fullpath)) {
               echo "<tr><td style=\"width: 3em; text-align: center;\">";
               echo "<input ";
               if ($f == vjinfo('theme')) {
                  echo 'checked="checked" ';
               }
               echo "type=\"radio\" name=\"theme\" id=\"theme_$f\" value=\"$f\" />&nbsp;";
               echo "</td><td style=\"white-space: pre;\"><strong><label for=\"theme_$f\">$f</label></strong>";
               if ($f == vjinfo('theme')) {
                  echo "<br />（目前選用的佈景主題）";
               }
               echo "</td>";
               show_info($fullpath);
               echo "<td style=\"white-space: pre;\"><a href=\"file-preview.php?theme=$f\" class=\"viewpage\">預覽佈景主題</a></td>";
               echo "</tr>";
            }
         }
         echo "</table>";
         echo "<p>";
         echo "<input type=\"submit\" name=\"submit\" value=\"選用佈景主題\" />&nbsp;";
         echo "<input type=\"reset\" name=\"reset\" value=\"回復到預設值\" />";
         echo "<input type=\"hidden\" name=\"action\" value=\"theme\" />";
         echo "</p>";
         echo "</form>";
         ?>
</div>
<div id="file_edit" class="wrap">
    <a name="edit"></a>
    <h3>編輯網頁範本檔案</h3>
    <p>您目前選用的佈景主題為 <strong><?php echo $config['theme'] ?></strong>，您可以透過網頁介面，編輯這個佈景主題中所使用的 PHP 程式檔案以及 CSS 樣式表。</p>
    <p>在編輯時請注意：副檔名為 PHP 的檔案，也是 PHP
        程式，如果您不小心改錯了內容，很有可能因為語法錯誤而造成無法執行，因此，在編輯時請務必小心。此外，您的佈景主題目錄、以及目錄中的檔案必須有允許寫入的權限，否則系統無法寫入您編輯過的內容。</p>
    <p>請點選以下頁面，進行編輯：</p>
    <?php

         $filenames = array();
         $filename['toc.php'] = "各期文章列表索引頁";
         $filename['post.php'] = "單篇文章顯示頁面";
         $filename['style.css'] = "網頁用樣式表";
         $filename['print.css'] = "列印用樣式表";
         $filename['vj.js'] = "共用 Javascript 檔案";
         $filename['header.php'] = "每頁頁首共用內容";
         $filename['footer.php'] = "每頁頁尾共用內容";

         $d = opendir($config['theme_path']);
         echo "<ul>\n";
         while ($f = readdir($d)) {
            $fullpath = $config['theme_path'] . $f;
            if (is_dir($fullpath))
               continue;
            $contenttype = mime_content_type($fullpath);
            if (!strstr($contenttype, "text"))
               continue;
            echo "<li>";
            echo "<a href=\"file-edit?page=" . $f . "\"> 編輯 ";
            if ($filename[$f]) {
               echo $filename[$f];
            } else {
               echo "$f"; // replace with db insert
            }
            echo "</a>";
            if (!is_writable($fullpath)) {
               echo " (無法寫入）";
            }
            echo "</li>\n";
         }
         echo "</ul>\n";
}
?>
</div>
<?php admin_footer(); ?>