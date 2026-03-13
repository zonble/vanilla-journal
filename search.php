<?php
include("vj-header.php");
$vj->thispage = "search";
vj_header("資料搜尋");
?>
<script type="text/javascript">
function checkall(input1, type) {
    var objForm = document.forms[input1];
    var objLen = objForm.length;
    for (var iCount = 0; iCount < objLen; iCount++) {
        if (type == true) {
            if (objForm.elements[iCount].type == "checkbox") {
                objForm.elements[iCount].checked = true;
            }
        } else {
            if (objForm.elements[iCount].type == "checkbox") {
                objForm.elements[iCount].checked = false;
            }
        }
    }
}
</script>

<h2>資料搜尋</h2>
<?php

$keyword = $_POST['keyword'];
$author = $_POST['author'];
$submit = $_POST['submit'];
if ($submit) {
   if ($submit == "根據作者檢索" && $author) {
      $query = "SELECT ID, TOPIC, VOLUME FROM $vjdb->post WHERE AUTHOR LIKE \"%$author%\"";
      $results = $vjdb->get_results($query, ARRAY_A);
   } else if ($submit == "根據關鍵字檢索" && $keyword) {
      $query = "SELECT ID, TOPIC, VOLUME FROM $vjdb->post WHERE TOPIC LIKE \"%$keyword%\" OR BODY LIKE \"%$keyword%\" OR KEYWORD LIKE  \"%$keyword%\"";
      $results = $vjdb->get_results($query, ARRAY_A);
   } else if ($submit == "搜尋" && $keyword) {
      $query = "SELECT ID, TOPIC, VOLUME FROM $vjdb->post WHERE TOPIC LIKE \"%$keyword%\" OR BODY LIKE \"%$keyword%\" OR KEYWORD LIKE  \"%$keyword%\"";
      $results = $vjdb->get_results($query, ARRAY_A);
   } else {
      echo "<h3>您沒有輸入搜尋條件！</h3>";
   }
   if (empty($results)) {
      echo "<p>對不起，沒有任何搜尋結果！</p>";
   } else {
      if ($submit == "根據作者檢索") {
         echo "<h3>搜尋作者「" . $author . "」的搜尋結果：</h3>";
      } else if ($submit == "根據關鍵字檢索") {
         echo "<h3>搜尋關鍵字「" . $keyword . "」的搜尋結果：</h3>";
      }
      $str = "";
      foreach ($results as $result) {
         if (cmp_published($result['VOLUME'])) {
            $str .= "<tr>";
            $str .= "<td class=\"search_check\"><input type=\"checkbox\" name=\"post-" . $result['ID'] . "\" value=\"" . $result['ID'] . "\">";
            $str .= "&nbsp;</td><td><a href=\"" . vjinfo('url') . "index.php?id=" . $result['ID'] . "\"";
            $str .= " title=\"" . $result['TOPIC'] . "\">" . $result['TOPIC'] . "</a>";
            $str .= "</td></tr>";
         } else if (is_logined()) {
            $str .= "<tr>";
            $str .= "<td class=\"search_check\"><input type=\"checkbox\" name=\"post-" . $result['ID'] . "\" value=\"" . $result['ID'] . "\">";
            $str .= "&nbsp;</td><td><a href=\"" . vjinfo('url') . "index.php?id=" . $result['ID'] . "\"";
            $str .= " title=\"" . $result['TOPIC'] . "\">" . $result['TOPIC'] . "</a>";
            $str .= " [尚未上線]</td></tr>";
         }
      }
      if ($str) {
         echo "<form method=\"post\" action=\"vj-endnotel.php\" name=\"form1\" id=\"form1\">";
         echo "<p>符合您的搜尋條件的文章表列如下：</p><table class=\"search_result\"><tr><th>選擇</th><th>文章標題</th></tr>" . $str . "</table>";
         echo '<p class="search_tool"><input type="button" onclick="checkall(\'form1\', 1)" value="選擇全部">';
         echo '<input type="button" onclick="checkall(\'form1\', 0)" value="全部不選">';
         echo '<input type="submit" value="將選擇的文章匯出成 Endnote 匯出格式">';
         echo "</p></form>";
      } else {
         echo "<p>對不起，沒有任何搜尋結果！</p>";
      }
   }
}
if (!$author)
   $author = urldecode($_GET['author']);
if (!$keyword)
   $keyword = urldecode($_GET['keyword']);

?>
<h3>您的搜尋條件：</h3>
<p>請在下方表單中，輸入您要搜尋的關鍵字，或是搜尋找出某位作者的文章。</p>
<table>
    <form method="post" action="search.php">
        <tr>
            <td><label for="author">作者：</label></td>
            <td><input size="30" type="text" id="author" name="author" value="<?php echo $author; ?>" /></td>
            <td><input type="submit" name="submit" style="width: 14em;" value="根據作者檢索" /></td>
    </form>
    </tr>

    <tr>
        <form method="post" action="search.php">
            <td><label for="keyword">關鍵字：</label></td>
            <td><input size="30" type="text" id="keyword" name="keyword" value="<?php echo $keyword; ?>" /></td>
            <td><input type="submit" name="submit" style="width: 14em;" value="根據關鍵字檢索" /></td>
    </tr>
    </form>
</table>
<?php vj_footer(); ?>