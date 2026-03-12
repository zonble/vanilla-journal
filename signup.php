<?php 
   include("vj-header.php");
   $vj->thispage = "subscribe";

   function signform($email, $name){
   ?>
   <form method="post">
   <p>
   <label for="email">信箱：</label><input id="email" name="email" value="<?php echo $email ?>" size="50"/><br />
   <label>姓名：</label><input id="name" name="name" value="<?php echo $name ?>"/>（可不填）</p>
   <p>
   <input type="submit" name="submit" value="訂閱"/>
   <input type="submit" name="submit" value="退訂"/>
   <input type="submit" name="submit" value="重新寄出認證信"/>
   </p>
   </form>
   <?php
   }

   function addbody($hash, $id) {
      $body = "<h2>電子報訂閱</h2>";
      $body .= "<p>您好，如果您想要訂閱「".vjinfo('title')."」，請點選下方連結，完成訂閱程序。</p>";
      $body .= "<p>".vjinfo('publisher')."敬啟</p>";
      $body .= "<p>".vjinfo('url')."signinfo.php?action=verify&amp;id=".$id."&amp;hash=".$hash;
      return $body;
   }

   function addtextbody($hash, $id) {
      $body = "電子報訂閱\n\n";
      $body .= "您好，如果您想要訂閱「".vjinfo('title')."」，請點選下方連結，完成訂閱程序。\n\n";
      $body .= vjinfo('publisher')."敬啟\n\n";
      $body .= vjinfo('url')."signinfo.php?action=verify&amp;id=".$id."&amp;hash=".$hash;
      return $body;
   }

   function rembody($hash, $id) {
      $body = "<h2>電子報退訂</h2>";
      $body .= "<p>您好，如果您想要退訂「".vjinfo('title')."」，請點選下方連結，完成退訂程序。</p>";
      $body .= "<p>".vjinfo('publisher')."敬啟</p>";
      $body .= "<p>".vjinfo('url')."signinfo.php?action=reject&amp;id=".$id."&amp;hash=".$hash;
      return $body;
   }

   function remtextbody($hash, $id) {
      $body = "電子報退訂\n\n";
      $body .= "您好，如果您想要退訂「".vjinfo('title')."」，請點選下方連結，完成退訂程序。\n\n";
      $body .= vjinfo('publisher')."敬啟\n\n";
      $body .= vjinfo('url')."signinfo.php?action=reject&amp;id=".$id."&amp;hash=".$hash;
      return $body;
   }

   function send_mail($body = "", $textbody ="", $title = "", $email, $name) {
      $mail = new PHPMailer();
      $mail->From     = vjinfo('sender');
      $mail->FromName = vjinfo('sender_name');

      $mail->Body    = $body;
      $mail->AltBody = $text_body;
      $mail->Subject = $title;
      $mail->IsHTML(1);
      $mail->AddAddress($email, $name);

      if(!$mail->Send())
      echo "<p>寄送電子報時發生錯誤！</p>";

      // Clear all addresses and attachments for next loop
      $mail->ClearAddresses();
      $mail->ClearAttachments();
   }

   $submit = $_POST['submit'];
   if($submit) {
      $email = $_POST['email'];
      $name = $_POST['name'];
      if(empty($email)) {
	 include("theme/header.php");
	 echo "<h2>訂閱、退訂電子報</h2>";
	 echo "<p>輸入錯誤，請輸入電子郵件信箱。</p>";
	 signform($email, $name);
	 include("theme/footer.php");
	 die();
      } else if(!check_email_address($email)) {
	 include("theme/header.php");
	 echo "<h2>錯誤！</h2>";
	 echo "<p>您輸入的電子郵件信箱格式不正確！請重新輸入！</p>";
	 signform($email, $name);
	 include("theme/footer.php");
	 die();
      } else {
	 if($submit == "訂閱") {
	    $query = "SELECT EMAIL FROM $vjdb->subscribers WHERE EMAIL='$email'";
	    $oldemail = $vjdb->get_var($query);
	    if($oldemail) {
	       vj_header();
	       echo "<h2>錯誤！</h2>";
	       echo "<p>您輸入的電子郵件信箱已經存在於資料庫中！請不要重複輸入！</p>";
	       signform($email, $name);
	       vj_footer();
	    }
	    $hash = md5($email.time());
	    $query = "INSERT INTO $vjdb->subscribers (EMAIL, NAME, HASH, VERIFIED) ";
	    $query .= "VALUES ('$email', '$name', '$hash', '0')";
	    $vjdb->query($query);
	    $query = "SELECT ID FROM $vjdb->subscribers ORDER BY ID DESC LIMIT 1 ";
	    $id = $vjdb->get_var($query);
	    $body = addbody($hash, $id);
	    $textbody = addtextbody($hash, $id);
	    send_mail($body, $textbody,"訂閱電子報", $email, $name);

	    $str = "<h2>訂閱電子報</h2>";
	    $str .= "<p>您好！我們已經將您的信箱，加入到我們的資料庫中了，不過，訂閱作業並沒有結束。為了確定您可以收到我們之後所發送的電子報，我們寄了一封信到您的信箱 <strong>".$email."</strong> 中，告訴您怎樣完成訂閱。請您查看信箱中是否已經有了這封信，以完成訂閱。</p>";
	    vj_die($str, "訂閱電子報");
	 }  else if($submit == "重新寄出認證信") {
	    $query = "SELECT ID, EMAIL, HASH, VERIFIED FROM $vjdb->subscribers WHERE EMAIL='$email'";
	    $oldemail = $vjdb->get_row($query, ARRAY_A);
	    if(!$oldemail) {
	       vj_header();
	       echo "<h2>重新寄出認證信失敗</h2>";
	       echo "<p>系統中沒有您的訂閱資料，無法重新寄出認證信！或許您可以考慮重新以其他的電子郵件信箱訂閱。</p>";
	       signform($email, $name);
	       vj_footer();
	    }
	    if($oldemail['VERIFIED']) {
	       $str = "<h2>您已經通過認證，不需要再寄給您認證信了！</h2>";
	       $str .= "<p>您好！因為您已經通過認證，所以我們不會寄給您認證信，以後，如果本刊有任何更新，都會寄送給您。</p>";
	       vj_die($str, "訂閱電子報");
	    }

	    $hash = $oldemail['HASH'];
	    $id = $oldemail['ID'];
	    $body = addbody($hash, $id);
	    $textbody = addtextbody($hash, $id);
	    send_mail($body, $textbody,"訂閱電子報", $email, $name);
	    $str = "<h2>認證信已經重新送出！</h2>";
	    $str .= "<p>您好！我們已經將您的信箱，加入到我們的資料庫中了，不過，訂閱作業並沒有結束。為了確定您可以收到我們之後所發送的電子報，我們寄了一封信到您的信箱 <strong>".$email."</strong> 中，告訴您怎樣完成訂閱。請您查看信箱中是否已經有了這封信，以完成訂閱。</p>";
	    vj_die($str, "訂閱電子報");
	 } 
	 else if($submit == "退訂") {
	    $query = "SELECT ID, HASH FROM $vjdb->subscribers WHERE EMAIL='$email'";
	    $result = $vjdb->get_results($query, ARRAY_A);
	    // print_r($result);
	    vj_header("退訂電子報");
	    if(!$result) {
	       echo "<h2>退訂電子報</h2>";
	       echo "<p>資料庫中並沒有 <strong>".$email."</strong> 這個信箱的訂閱紀錄，您是否打錯字了呢？</p>";
	       signform($email, $name);
	    } else {
	       $info = $result[0];
	       echo "<h2>退訂電子報</h2>";
	       echo "<p>您好！為了確定是您本人要退訂電子報，我們寄了一封信到您的信箱 <strong>".$email."</strong> 中，這封信中包含了怎樣完成訂閱的程序。請您查看信箱中是否已經有了這封信，以完成退訂。</p>";
	       $hash = $info['HASH'];
	       $id = $info['ID'];
	       $body = rembody($hash, $id);
	       $textbody = remtextbody($hash, $id);
	       send_mail($body, $textbody,"退訂電子報", $email, $name);
	    }
	    vj_footer();
	 }
      }

   } else {
      vj_header("訂閱、退訂電子報");
      echo "<h2>訂閱、退訂電子報</h2>";
      echo "<p>您好，您除了可以透過網頁瀏覽期刊內容外，我們也可以透過電子郵件，將最新的內容寄送給您。如果您想要訂閱電子報，請在下方輸入您的電子郵件信箱以及姓名，然後選擇訂閱，反之，如果您不願意繼續收到我們所寄送的電子報，您也可以在下面的表單中選擇退訂。我們保證，您的電子郵件信箱等個人資料僅用於寄送本刊的內容，而不會用在其他不正當用途上。</p>";
      echo "<p>因為要確定是您本人要訂閱或退訂，因此，在您填寫了電子郵件信箱資料之後，我們會先寄送一封認證信件，到您的信箱當中，這封信中包含了一個網址，只要點選網址，就可以完成訂閱或退訂。如果您一直沒有收到這封認證信件，請您輸入您的電子郵件信箱，然後按下「重新寄出認證信」。";
      signform("", "");
      vj_footer();
   }
?>
