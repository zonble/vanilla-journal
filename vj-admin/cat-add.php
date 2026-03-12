<?php
   include("admin.php");
   admin_header("新增單元");
?>
<div class="wrap">
    <form method="post" action="cats.php">
        <h2>新增單元</h2>
        <p>請問您要新增的單元是？
        <p>
            <?php cats_form("", "", "新增單元", "add-cat"); ?>
</div>

<?php 
   admin_footer();
?>