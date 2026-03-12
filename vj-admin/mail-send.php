<?php
   require("admin.php");
   $refer = $_SERVER['HTTP_REFERER'];
   $submit = $_POST['submit'];
   if(empty($submit)) {
      if($refer) {
	 header("Location: $refer");
      } else {
	 admin_die("<h2>錯誤</h2><p>您不可以直接瀏覽此頁</p>", "錯誤");
      }
   }

   function send_paper($volume = "", $emails, $names) {
      global $config;

      $uploadurl = $config['image_url'];
      $uploadpath = $config['image_path'];

      $url = vjinfo('url')."index.php?is_email=1";

      $mail = new PHPMailer();
      $mail->From = vjinfo('sender');
      $mail->FromName = vjinfo('sender_name');
      if($volume) {
	 $url = $url."&amp;volume=".$volume;
      }

      $body = file_get_contents($url);

      preg_match_all("/".str_replace("/", "\/",$uploadurl)."(.+).jpg/", $body, $matches);
      $images_url = $matches[0];
      $images_cid = $matches[1];
      $i =0;
      foreach($images_url as $image_url){
	 $body = str_replace($image_url, "cid:".$images_cid[$i], $body); 
	 $mail->AddEmbeddedImage($uploadpath.$images_cid[$i].".jpg", $images_cid[$i], $images_cid[$i].".jpg");
	 $i++;
      }
      preg_match("/<title>(.+)<\/title>/", $body, $match);
      $title = $match[1]; 

      /* $text_body  = "Hello " . $row["full_name"] . ", \n\n";
      $text_body .= "Your personal photograph to this message.\n\n";
      $text_body .= "Sincerely, \n";
      $text_body .= "PHPMailer List manager"; */

      $mail->Body    = $body;
      $mail->AltBody = $text_body;
      $mail->Subject = $title;
      $mail->IsHTML(1);
      $i = 0;
      foreach($emails as $email) {
	 // $mail->AddAddress($email, $names[$i]);
	 $mail->AddBCC($email, $names[$i]);
	 $i ++;
      }

      if(!$mail->Send())
      echo "<p>寄送電子報時發生錯誤！</p>";

      $mail->ClearAddresses();
      $mail->ClearAttachments();
   }

   $action = $_POST['action'];
   $volume = $_POST['volume'];
   $emails = array();
   $names = array();

   if($action == 'test' ) {
      $email = $_POST['email'];
      $emails[0] = $email;
      $names[0] = vjinfo('title').'測試信箱';
      admin_header("寄出測試電子報");
      send_paper($volume, $emails, $names);
      echo "<h2>已經寄發出測試電子報</h2>";
      echo "<p>測試電子報已經寄到了 ".$email." 信箱，請打開信箱查看效果！</p>";
      admin_footer();
   } else {
      $query = "SELECT EMAIL, NAME FROM $vjdb->subscribers WHERE VERIFIED='1' ORDER BY EMAIL ASC";
      $results = $vjdb->get_results($query, ARRAY_A);
      $i = 0;
      foreach($results as $result) {
	 $emails[$i] = $result['EMAIL'];
	 $names[$i] = $result['NAME'];
	 $i++;
      }
      admin_header("寄出電子報");
      send_paper($volume, $emails, $names);
      echo "<h2>已經寄發出電子報！</h2>";
      echo "<p>測試電子報已經寄到了以下信箱：</p>";
      echo "<ul>\n";
      foreach($emails as $email) {
	 echo "<li>".$email."</li>\n";
      }
      echo "</ul>\n";
      admin_footer();
   }

?>