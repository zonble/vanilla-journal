<?php
   include("admin.php");
   $pagetitle = "匯入訂戶資料";

   $action = $_POST['action'];
   if($action == 'add-email') {
      $emails = explode("\n", $_POST['emails']);
      $emails_good = array();
      $emails_bad = array();
      $emails_repeat = array();
      $emails_good_count = 0;
      $emails_bad_count = 0;
      $emails_repeat_count = 0;
      foreach($emails as $email) {
	 $email = str_replace("\n", "", $email);
	 $email = str_replace("\r", "", $email);
	 if(empty($email)) {
	    continue;
	 } else if(!check_email_address($email)) {
	    $emails_bad[++$emails_bad_count] = $email;
	 } else {
	    $query = "SELECT EMAIL FROM $vjdb->subscribers WHERE EMAIL='$email'";
	    $oldemail = $vjdb->get_var($query);
	    if($oldemail) {
	       $emails_repeat[++$emails_repeat_repeat] = $email;
	    } else {
	       $hash = md5($email.time());
	       $query = "INSERT INTO $vjdb->subscribers (EMAIL, NAME, HASH, VERIFIED) ";
	       $query .= "VALUES ('$email', '$name', '$hash', '1')";
	       $vjdb->get_var($query);
	       $emails_good[++$emails_good_repeat] = $email;
	    }
	 }
      }
      admin_header();
      echo "<h2>訂戶信箱資料匯入完畢！</h2>";
      echo "<p>這次匯入作業的結果如下：</p>";
      echo "<h3>成功輸入的信箱資料：</h3>\n";
      if($emails_good) {
	 echo "<ul>";
	 foreach($emails_good as $email) {
	    echo "<li>".$email."</li>\n";
	 }
	 echo "</ul>";
      } else {
	 echo "<p>無</p>";
      }
      echo "<h3>重複輸入的信箱資料：</h3>\n";
      if($emails_repeat) {
	 echo "<ul>";
	 foreach($emails_repeat as $email) {
	    echo "<li>".$email."</li>\n";
	 }
	 echo "</ul>";
      } else {
	 echo "<p>無</p>";
      }
      echo "<h3>格式錯誤的信箱資料：</h3>\n";
      if($emails_bad) {
	 echo "<ul>";
	 foreach($emails_bad as $email) {
	    echo "<li>".$email."</li>\n";
	 }
	 echo "</ul>";
      } else {
	 echo "<p>無</p>";
      }

      admin_footer();
   } else {
      admin_header();
   ?>

<div class="wrap">
<h2>匯入訂戶電子郵件信箱</h2>
<div class="tool">
新增帳戶：
<a href="subscriber-add.php" title="單一輸入">單一輸入</a>
<a>大量輸入</a>
</div>
<p>請在下方輸入您要新增的訂戶電子郵件信箱，每一行輸入一個電子郵件信箱。</p>
<form method="post">
<textarea name="emails" id="emails" rows="10" style="width: 90%; margin: 10px;"></textarea><br />
<input type="hidden" name="action" value="add-email" />
<input type="submit" name="submit" id="submit" value="匯入電子郵件信箱" />
</form>
</div>

<?php 
   }
   admin_footer();
?>
