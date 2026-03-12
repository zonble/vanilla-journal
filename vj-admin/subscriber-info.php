<?php
   include("admin.php");
   $pagetitle = "設定訂戶資料";

   $error = 0;
   $submit = $_POST['submit'];
   if(empty($submit)) {
      admin_header();
      $refer = $_SERVER['HTTP_REFERER'];
      echo "<h2>設定訂戶資料</h2>";
      $id = $_GET['id'];
      if($id) {
	 $query = "SELECT * FROM $vjdb->subscribers WHERE ID='$id'";
	 $result = $vjdb->get_results($query, ARRAY_A);
	 $info = $result[0];
	 if($info) {
	    $email = $info['EMAIL'];
	    $name = $info['NAME'];
	    $verified = $info['VERIFIED'];
	    subscriber_form($email, $name, $refer, $id, $verified);
	 } else {
	    echo "對不起，沒有符合這個 ID 的訂戶。";
	 }
      } else {
	 echo "請指定要編輯的訂戶 ID";
      }
   } else {
      $id = $_POST['id'];
      $email = $_POST['email'];
      $name = $_POST['name'];
      $refer = $_POST['refer'];
      $verified = $_POST['verified'];

      if(empty($email)) {
	 admin_header();
	 echo "<h2>錯誤！</h2>";
	 echo "<p>您沒有輸入您要新增的電子郵件信箱！請重新輸入！</p>";
	 subscriber_form($email, $name, $refer, $id, $verified);
	 die(admin_footer());
      } else if(!check_email_address($email)) {
	 admin_header();
	 echo "<h2>錯誤！</h2>";
	 echo "<p>您輸入的電子郵件信箱格式不正確！請重新輸入！</p>";
	 subscriber_form($email, $name, $refer, $id, $verified);
	 die(admin_footer());
      } else {
	 $query = "SELECT ID, EMAIL FROM $vjdb->subscribers WHERE EMAIL='$email'";
	 $oldemail = $vjdb->get_results($query, ARRAY_A);
	 if(count($oldemail) > 1 || (count($oldemail) == 1 && $oldemail[0][ID] != $id)) {
	    admin_header();
	    echo "<h2>錯誤！</h2>";
	    echo "<p>您輸入的電子郵件信箱已經存在於資料庫中！請不要重複輸入！</p>";
	    subscriber_form($email, $name, $refer, $id, $verified);
	    die(admin_footer());
	 }
      }

      $query = "UPDATE $vjdb->subscribers SET EMAIL ='$email', NAME = '$name', VERIFIED = '$verified' WHERE ID='$id'";
      $vjdb->get_var($query);
      $refer = $_POST['refer'];
      if($ajax) {
	 admin_header();
	 echo "<h2>更新完畢！</h2>";
	 echo "<p>已經成功更新訂戶資料！</p>";
      } else if($refer) {
	 header("Location: $refer");
      } else {
	 admin_header();
	 echo "<h2>更新完畢！</h2>";
	 echo "<p>已經成功更新訂戶資料！</p>";
      }
   }
?>
<?php admin_footer(); ?>
