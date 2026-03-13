<?php
include("admin.php");
admin_header("管理首頁");
$query = "SELECT ID FROM $vjdb->volumes LIMIT 1";
$withvolumes = $vjdb->get_var($query);
$query = "SELECT ID, ALIAS FROM $vjdb->volumes WHERE PUBLISHED='1' ORDER BY CREATE_DATE DESC LIMIT 1";
$newpublished = $vjdb->get_row($query, ARRAY_A);
$query = "SELECT ID, ALIAS FROM $vjdb->volumes WHERE PUBLISHED='0' ORDER BY CREATE_DATE DESC LIMIT 1";
$newnotpublished = $vjdb->get_row($query, ARRAY_A);
?>
<div class="wrap">
    <h2>Welcome, Willkommen, 歡迎光臨！</h2>
    <p>歡迎進入 Vanilla Journal 的系統管理介面。</p>
    <p>在這個系統中，您除了可以透過瀏覽器，在線上輸入文稿、上傳圖片，輕鬆建立線上的期刊，更可以透過電子郵件，將您的線上期刊的當期索引目錄，寄送給您的訂戶，讓他們在打開信箱的時候，就可以看到線上期刊的內容。</p>
    <div style="width: 40%; float: right; padding: 10px; border: 1px solid #CCC; font-size: 10pt;">
        <h3>不知道怎麼使用嗎？</h3>
        <p>如果您才剛開始使用這個系統，還不熟悉功能，您可以先閱讀<a href="help.php">輔助說明</a>。
    </div>
    <h3>請問您現在想要做些什麼呢？</h3>
    <p>您現在可以：</p>
    <ul>
        <li>開始準備發行一期新的期刊</li>
        <ul>
            <li>請點選<a href="volume-add.php">新增一期期刊</a></li>
        </ul>
        <?php if ($withvolumes) { ?>
        <?php
         if ($newnotpublished) {
            echo "<li>管理最新一期尚未上線的期刊：<strong>第 " . $newnotpublished['ALIAS'] . " 期</strong></li>";
            echo "<ul>";
            echo "<li><a href=\"volume-info.php?id=" . $newnotpublished['ID'] . "\">管理設定</a></li>";
            echo "<li><a href=\"post-add.php?volume=" . $newnotpublished['ID'] . "\">新增文章</a></li>";
            echo "<li><a href=\"volume-edit.php?volume=" . $newnotpublished['ID'] . "\">編輯文章</a></li>";
            echo "<li><a href=\"import.php?volume=" . $newnotpublished['ID'] . "\">從 RSS 匯入文章</a></li>";
            echo "<li><a href=\"mail.php?volume=" . $newnotpublished['ID'] . "\">寄出電子報</a></li>";
            echo "</ul>";
         }
         if ($newpublished) {
            echo "<li>管理最新一期已上線的期刊：<strong>第 " . $newpublished['ALIAS'] . " 期</strong></li>";
            echo "<ul>";
            echo "<li><a href=\"volume-info.php?id=" . $newpublished['ID'] . "\">管理設定</a></li>";
            echo "<li><a href=\"post-add.php?volume=" . $newpublished['ID'] . "\">新增文章</a></li>";
            echo "<li><a href=\"volume-edit.php?volume=" . $newpublished['ID'] . "\">編輯文章</a></li>";
            echo "<li><a href=\"import.php?volume=" . $newpublished['ID'] . "\">從 RSS 匯入文章</a></li>";
            echo "<li><a href=\"mail.php?volume=" . $newpublished['ID'] . "\">寄出電子報</a></li>";
            echo "</ul>";
         }
      } ?>
        <?php
      $query = "SELECT ID, TOPIC FROM $vjdb->post ORDER BY POST_DATE DESC LIMIT 5";
      $posts = $vjdb->get_results($query, ARRAY_A);
      if ($posts) {
         echo "<li>編輯最近新增的文章</li><ul>";
         foreach ($posts as $post) {
            if ($post['TOPIC']) {
               $topic = $post['TOPIC'];
            } else {
               $topic = "post" . $post['ID'];
            }
            echo "<li><a href=\"" . $config['post_editlink'] . $post['ID'] . "\" title=\"編輯〈" . $post['TOPIC'] . "〉\">編輯〈" . $post['TOPIC'] . "〉</a></li>";
         }
         echo "</ul>";
      }
      ?>
    </ul>

    <?php
   admin_footer();
   ?>