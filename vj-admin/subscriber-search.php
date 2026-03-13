<?php
include("admin.php");
admin_header("搜尋訂戶");
$keyword = trim($_POST['keyword']);
?>

<div class="wrap">
    <h2>以關鍵字搜尋訂戶</h2>
    <p>請問您要搜尋的關鍵字是？
    <p>
        <?php searchemail_form($keyword); ?>
        <?php
      if ($keyword) {
         echo "<h3>搜尋關鍵字「" . $keyword . "」的結果如下：</h3>";
         echo "<div id=\"subscribe_table\">\n";
         subscribe_table(2, $keyword);
         echo "</div>";
      }

      ?>
</div>

<?php
admin_footer();
?>