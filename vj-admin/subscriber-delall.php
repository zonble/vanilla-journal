<?php
   include("admin.php");
   admin_header("刪除所有沒有通過認證的訂戶信箱");

   $submit = $_POST['submit'];
   $action = $_POST['action'];
   if($submit && $action == 'delall') { ?>
<div id="wrap">
    <h2>刪除所有沒有通過認證的訂戶信箱</h2>
    <?php
      $query = "DELETE FROM $vjdb->subscribers WHERE VERIFIED = '0'";
      if($vjdb->query($query)) {
	 echo "<p>刪除完畢！</p>";
      } else {
	 echo "<p>在刪除時發生錯誤！</p>";
      }
   ?>
    <p>回到<a href="subscribers.php">訂戶資料管理頁面</a>。</p>
</div>
<?php } else { ?>
<div id="wrap">
    <form method="post">
        <h2>刪除所有沒有通過認證的訂戶信箱</h2>
        <p>雖然系統不會在寄發電子報的時候，將電子報寄給沒有通過認證的用戶。不過，如果刪除這些電子郵件信箱資料，還是會比較方便管理。</p>
        <p>您確定要刪除刪除所有沒有通過認證的訂戶信箱嗎？</p>
        <input type="submit" name="submit" value="刪除所有沒有通過認證的訂戶信箱" />
        <input type="hidden" name="action" value="delall" />
    </form>
</div>
<?
   }
   admin_footer();
?>