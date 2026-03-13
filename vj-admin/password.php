<?php
include("admin.php");

admin_header("修改系統密碼");
$submit = $_POST['submit'];
if ($submit) {
   $password = vjinfo('password');
   $password1 = $_POST['password1'];
   $password2 = $_POST['password2'];
   $oldpassword = $_POST['oldpassword'];
   if ($password1 != $password2) {
      echo "<h2>修改密碼時發生錯誤！</h2><p>您所輸入的兩次密碼並不相同！</p>";
   } else if (md5($oldpassword) != $password && ($password)) {
      echo "<h2>修改密碼時發生錯誤！</h2><p>舊密碼輸入錯誤！</p>";
   } else if (!$password1 && !$password2) {
      echo "<h2>沒有修改密碼</h2><p>您輸入了空白的新密碼，因此沒有更新密碼</p>";
   } else {
      $password = md5($password1);
      $query = "UPDATE $vjdb->info SET VALUE=\"$password\" WHERE KEY = 'password'";
      if ($vjdb->query($query)) {
         echo "<h2>密碼成功更新！</h2>";
         echo "<p>因為您變更了密碼，所以系統稍後可能會要求您重新登入，輸入新的密碼。</p>";
      } else {
         echo "<h2>密碼更新失敗！</h2>";
         echo "<p>在更新密碼的時候出現錯誤</p>";
      }
   }
   password_form($password);
   admin_footer();
}
?>
<h2>修改系統密碼</h2>
<p>如果您想要修改密碼，請輸入舊密碼認證，同時輸入兩次新密碼，如果輸入錯誤，則無法修改。</p>
<p>此外，如果密碼留空，代表不修改。</p>

<?php
password_form(vjinfo('password'));
admin_footer();
?>