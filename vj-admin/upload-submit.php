<?php
   include("admin.php");
   admin_header("上傳檔案");
   error_reporting(0);
   echo "<div id=\"uploading\">";
   echo "<h2><img src=\"images/indicator_medium.gif\" alt=\"Indicator\"/>";
   echo "&nbsp;檔案上傳中…</h2>";
   echo "<p>如果您發現無法正確上傳比較大的檔案，而是在上傳過程中會中途停止上傳，您可以試著把檔案縮小，或是修改 php 以及 apache 的相關設定。例如，在 PHP.INI 中，與檔案相關的參數就有 <code>max_execution_time</code>、<code>max_input_time</code>、<code>memory_limit</code>、<code>post_max_size</code>、<code>upload_max_filesize</code> 等，您可以試試看將這些參數的數值調大些。關於這些設定的詳細意義，請參見相關的 PHP 文件或是手冊。</p>";
   echo "<p>如果您無法自行修改 PHP.INI 的設定，請聯絡您所租用空間的服務商，或是貴單位的伺服器系統管理員。</p>";
   echo "</div>";

   $volumeid = $_POST['volumeid'];
   if(empty($volumeid)) {$volumeid =(int)0; }
   $postid = $_POST['postid'];
   $tagline = $_POST['tagline'];
   $upload_url = $config['image_url'];
   $upload_path = $config['image_path'];
   $attach_url = $config['attach_url'];
   $attach_path = $config['attach_path'];
   $size = array();

   $thisyear = date("Y");
   $thismonth = date("m");
   $path = date("Y/m/");

   function hide_uploading() {
      echo '<script type="text/javascript">';
      // echo 'new Effect.Fade("uploading");';
      echo '$("uploading").style.display = "hidden";';
      echo '</script>';
   }

   function make_path($path) {
      if(file_exists($path)) {
	 if(!is_dir($path)) {
	    return false;
	 }
	 return true;
      } else {
	 mkdir($path);
	 return true;
      }
      return false;
   }

   function check_path($path) {
      if(!make_path($path)) {
	 echo "<h2>錯誤！無法建立上傳目錄！</h2>";
	 admin_footer();
      }
   }


   if ($_POST['action'] == 'image') {
      $handle = new Upload($_FILES['userfile']);
      check_path($upload_path.$thisyear);
      check_path($upload_path.$path);
      $upload_path .= $path;
      $upload_url .= $path;

      if ($handle->uploaded) {
	 hide_uploading();
	 $query = "SELECT ID from $vjdb->images ORDER BY ID DESC LIMIT 1";
	 $newpicid = $vjdb->get_var($query);
	 $newpicid = $newpicid + 1;
	 if(vjinfo('image_max')) {
	    $image_max = vjinfo('image_max');
	 } else {
	    $image_max = 350;
	 }
	 if(vjinfo('thumb_max')) {
	    $thumb_max = vjinfo('thumb_max'); 
	 } else {
	    $thumb_max = 100;
	 }

	 $handle->file_max_size = 10 * 1024 * 1024;

	 /* We do not create a thumb image if GD library does not 
	    exist. */
	 if($handle->gd_version()) {
	    $handle->image_convert        = 'jpg';
	    $handle->image_resize         = true;
	    $info = getimagesize($handle->file_src_pathname);
	    $image_x = $info[0];
	    $image_y = $info[1];

	    if($image_x > $image_y) {
	       $handle->image_ratio_y        = true;
	       $handle->image_x              = $thumb_max;
	    } else {
	       $handle->image_ratio_x        = true;
	       $handle->image_y              = $thumb_max;
	    }

	    $newpicname = "img-".$newpicid."-thumb";
	    $handle->file_new_name_body   = $newpicname;
	    $handle->Process($upload_path);
	    $mythumbname = $handle->file_dst_name;
	    $info = getimagesize($handle->file_dst_pathname);
	    $size['tw'] = $info[0];
	    $size['th'] = $info[1];
	 }

	 /* We do not convert the image size and file type
	    if the GD library does not exist. */
	 if($handle->gd_version()) {
	    $handle->image_convert        = 'jpg';
	    $handle->image_resize         = true;
	    if($image_x > $image_y) {
	       $handle->image_ratio_y        = true;
	       $handle->image_x              = $image_max;
	    } else {
	       $handle->image_ratio_x        = true;
	       $handle->image_y              = $image_max;
	    }
	 }

	 $newpicname = "img-".$newpicid;
	 $handle->file_new_name_body = $newpicname;
	 $handle->Process($upload_path);

	 // we check if everything went OK
	 if ($handle->processed) {
	    // everything was fine !

	    $myfilename = $handle->file_dst_name;
	    $info = getimagesize($handle->file_dst_pathname);
	    $size['w'] = $info[0];
	    $size['h'] = $info[1];
	    if(!$handle->gd_version()) {
	       $mythumbname = $handle->file_dst_name;
	       $size['tw'] = $info[0];
	       $size['th'] = $info[1];
	    }
	    $mysize = serialize($size);
	    $time = date("Y-m-d H:i:s");
	    $query = "INSERT INTO $vjdb->images (TAGLINE, FILENAME, FILEPATH, THUMB, VOLUMEID, POSTID, SIZE, UPLOAD_DATE, DISPLAY)";
	    $query .= " VALUES ('$tagline', '$myfilename', '$path', '$mythumbname', '$volumeid', '$postid', '$mysize', '$time', '1')";
	    if($vjdb->query($query)) {
	       echo '<h2>檔案上傳成功！</h2>';
	       echo '<p>您所上傳的圖片如下：</p>';
	       echo '  <img src="'.$upload_url.$mythumbname.'" alt="'.$handle->file_dst_name.'" class="uploadedimg"/>';
	       echo '  <p>檔案類型：' . $info['mime'] . ' &nbsp;- 圖片尺寸：' . $info[0] . ' x ' . $info[1] .' &nbsp;- 檔案大小：' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB</p>';
	       echo '  <p>本圖片網址：'. $upload_url . $handle->file_dst_name . '</p>';
	       if($volumeid != 0 && !($ajax)){
		  echo "<p><a href=\"volume-info.php?id=".$volumeid."\">回到這張圖片的所在電子報頁面</a></p>";
	       } else if($postid != 0) {
		  echo "<p><a href=\"upload.php?ajax=1&postid=".$postid."\">繼續上傳屬於這篇文章的所屬照片</a></p>";
		  if(!($ajax)) {
		     echo "<p><a href=\"post-edit.php?id=".$postid."\">回到這張圖片的所在文章頁面</a></p>";
		  }
	       }
	    } else {
	       echo '<h2>圖片上傳成功！</h2>';
	       echo '<p>不過資料庫資料建立失敗，請研究一下出了什麼問題。</p>';
	       echo '<p>要建立資料庫資料的 SQL 語法是：</p>';
	       echo '<p>'.$query.'</p>';
	    }
	 } else {
	    hide_uploading();
	    echo '<h2>圖片上傳失敗！</h2>';
	    echo '<p>失敗原因如下：</p>';
	    echo '<p>Error: ' . $handle->error . '</p>';
	 }

      } else {
	 hide_uploading();
	 echo '<h2>圖片上傳失敗！</h2>';
	 echo '<p>失敗原因如下：</p>';
	 echo '<p>Error: ' . $handle->error . '</p>';
      }
      // image
   } else if ($_POST['action'] == 'simple') {
      $handle = new Upload($_FILES['userfile']);
      check_path($attach_path.$thisyear);
      check_path($attach_path.$path);
      $attach_path .= $path;
      $attach_url .= $path;

      if ($handle->uploaded) {
	 hide_uploading();
	 $query = "SELECT ID from $vjdb->attaches ORDER BY ID DESC LIMIT 1";
	 $newfileid = $vjdb->get_var($query);
	 $newfileid = $newfileid + 1;
	 $newfilename = "attach".$newfileid;
	 $handle->file_new_name_body   = $newfilename;

	 $handle->Process($attach_path);
	 //print_r($handle);
	 if ($handle->processed) {
	    // everything was fine !
	    // print_r($handle);
	    $myfilename = $handle->file_dst_name;
	    $mysize = filesize($handle->file_dst_pathname);
	    $mytype = $handle->file_src_mime;
	    $time = date("Y-m-d H:i:s");
	    $query = "INSERT INTO $vjdb->attaches (TAGLINE, FILENAME, FILEPATH, POSTID, FILESIZE, FILETYPE, UPLOAD_DATE, DISPLAY)";
	    $query .= " VALUES ('$tagline', '$myfilename', '$path', '$postid', '$mysize', '$mytype', '$time', '1')";
	    if($vjdb->query($query)) {
	       echo '<h2>檔案上傳成功！</h2>';
	       echo '<p>您所上傳的檔案如下：</p>';
	       echo '<p>檔案大小：' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB</p>';
	       echo '<p>檔案連結：<a href="'. $attach_url.$handle->file_dst_name . '">' . $handle->file_dst_name . '</a></p>';
	       if($postid != 0) {
		  echo "<p><a href=\"attach.php?ajax=1&postid=".$postid."\">繼續上傳屬於這篇文章的所屬附件</a></p>";
		  if(!($ajax)) {
		     echo "<p><a href=\"post-edit.php?id=".$postid."\">回到這個檔案的所在文章頁面</a></p>";
		  }
	       }
	    } else {
	       echo '<h2>檔案上傳成功！</h2>';
	       echo '<p>不過資料庫資料建立失敗，請研究一下出了什麼問題。</p>';
	    }
	 } else {
	    echo '<h2>檔案上傳失敗！</h2>';
	    echo '<p>失敗原因如下：</p>';
	    echo '<p>Error: ' . $handle->error . '</p>';
	 }

      } else {
	 hide_uploading();
	 echo '<h2>檔案上傳失敗！</h2>';
	 echo '<p>失敗原因如下：</p>';
	 echo '<p>Error: ' . $handle->error . '</p>';
      }

   } else {
      echo "<h2>錯誤</h2>";
      echo "<p>您不可以直接瀏覽這個網頁！</p>";
   }

   admin_footer();
?>
