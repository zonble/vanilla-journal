<?php
/* 先看看要看的是哪一期 */
$this_volume = $_GET['volume'];
$volume = new volume($this_volume);
$volume->error();

$this_volume = $volume->volume_id;
vj_header();

if (!$ajax) {
   pub_link();
}
vj_toc();
if (is_logined() && !$ajax) {
   echo '<p style="font-size: 9pt; text-align:center;"><strong>管理功能</strong>：因為您是管理者，您可以';
   echo '<a href="' . vjinfo('url') . 'vj-admin/volume-info.php?id=' . $this_volume . '">設定本期資料</a>、';
   echo '<a href="' . vjinfo('url') . 'vj-admin/post-add.php?volume=' . $this_volume . '">新增本期的文章</a>、';
   echo '<a href="' . vjinfo('url') . 'vj-admin/volume-edit.php?volume=' . $this_volume . '">編輯本期的文章</a>。';
   echo "<p>";
}
vj_footer();
?>