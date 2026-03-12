<?php
   include("admin.php");
   admin_header("新增一名訂戶");
?>
<div class="wrap">
    <h2>新增一名訂戶</h2>
    <div class="tool">
        新增帳戶：
        <a>單一輸入</a>
        <a href="<?php info('url'); ?>vj-admin/subscriber-import.php" title="大量輸入">大量輸入</a>
    </div>
    <p>請問您要新增的訂戶的電子郵件信箱與名稱是？
    <p>
        <?php addemail_form('', ''); ?>
    <p>此外，您也可以一次大量輸入多筆訂戶的電子郵件信箱：<a href="<?php info('url'); ?>vj-admin/subscriber-import.php" title="大量輸入">大量輸入</a></p>
</div>

<?php 
   admin_footer();
?>