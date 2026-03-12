<?php
   include("admin.php");
   $pagetitle = "訂戶資料維護";

   $action = $_POST['action'];
   if($action) {
      switch($action){
	 case 'add-email': //新增訂戶
	    $email = $_POST['email'];
	    $name = $_POST['name'];
	    if(empty($email)) {
	       admin_header();
	       echo "<h2>錯誤！</h2>";
	       echo "<p>您沒有輸入您要新增的電子郵件信箱！請重新輸入！</p>";
	       addemail_form($email, $name);
	       admin_footer();
	    } else if(!check_email_address($email)) {
	       admin_header();
	       echo "<h2>錯誤！</h2>";
	       echo "<p>您輸入的電子郵件信箱格式不正確！請重新輸入！</p>";
	       addemail_form($email, $name);
	       admin_footer();
	    } else {
	       $query = "SELECT EMAIL FROM subscribers WHERE EMAIL='$email'";
	       $oldemail = $vjdb->get_var($query);
	       if($oldemail) {
		  admin_header();
		  echo "<h2>錯誤！</h2>";
		  echo "<p>您輸入的電子郵件信箱已經存在於資料庫中！請不要重複輸入！</p>";
		  addemail_form($email, $name);
		  admin_footer();
	       }
	       $hash = md5($email.time());
	       $query = "INSERT INTO $vjdb->subscribers (EMAIL, NAME, HASH, VERIFIED) ";
	       $query .= "VALUES ('$email', '$name', '$hash', '1')";
	       $vjdb->query($query);
	       header("Location: subscribers.php");
	    }
	    break;
	 default:
	    break;
      }
   } else {
      admin_header();
?>

<div class="wrap">
    <h2>訂戶資料維護</h2>
    <p>目前資料庫中的各期電子訂戶列表如下，您可以修改訂戶的名稱或電子郵件資料，或是刪除訂戶。</p>
    <p>您也可以選擇要<a href="subscribers.php">顯示所有訂戶</a>、<a href="subscriber-search.php">搜尋特定訂戶</a>、<a
            href="subscribers.php?opt=1">只顯示沒有通過認證的訂戶</a>、<a href="subscriber-add.php">新增訂戶</a>、或一次<a
            href="<?php echo vjinfo('url')?>vj-admin/subscriber-delall.php">刪除所有沒有通過認證的訂戶信箱</a>。</p>

    <?php 
      $opt = $_GET['opt'];
      echo "<div id=\"subscribe_table\">\n";
      subscribe_table($opt); // 訂戶列表
      echo "</div>";
?>
</div>
<?php /*
<div class="wrap">
<h2>新增一名訂戶</h2>
<p>請問您要新增的訂戶的電子郵件信箱與名稱是？<p>
<?php addemail_form('', ''); ?>
</div>
*/ ?>
<?php 
   } 
   admin_footer();
?>