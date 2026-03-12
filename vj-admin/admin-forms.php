<?php
/* 各種表單 */

/* 分類新增編輯表單 */

function cats_form($cat_name="", $cat_desc="", $submitstr="", $action="", $id=0) {
   global $ajax;
?>
   <form method="post" action="cats.php">
   <ul>
   <?php if($ajax) { ?><input type="hidden" name="ajax" value="1"> <?php } ?>
<li>&nbsp;<label for="cat_name">名稱</label>： <input type="text" size="20" id="cat_name" name="cat_name" value="<?php echo $cat_name; ?>"/> </li>
<li>&nbsp;<label for="cat_desc">附註</label>： <input type="text" size="40" id="cat_desc" name="cat_desc" value="<?php echo $cat_desc; ?>"/> </li>
   </ul>
   <input type="submit" value="<?php echo $submitstr; ?>"/>
   <input type="hidden" name="action" value="<?php echo $action; ?>"/>
   <input type="hidden" name="id" value="<?php echo $id ;?>"/>
   </form>
<?php
}

/* 修改密碼表單 */ 

function password_form($password="") {
   global $ajax;
?>
   <form method="post">
   <?php if($ajax) { ?><input type="hidden" name="ajax" value="1"> <?php } ?>
   <table>
   <tr><td class="options"><label for="password1">請輸入新密碼</label>：</td>
   <td><input type="password" size="20" name="password1" id="password1" /></td></tr>
   <tr><td class="options"><label for="password2">請再次確認您所輸入的密碼</label>：</td>
   <td><input type="password" size="20" name="password2" id="password2" /></td></tr>
<?php if($password) { ?>
   <tr><td class="options"><label for="oldpassword">請輸入目前的密碼</label>：</td>
   <td><input type="password" size="20" name="oldpassword" id="oldpassword" /></td></tr>
<?php } ?>
   <tr><td class="options">&nbsp;</td>
   <td><input type="submit" name="submit" value="修改密碼！" /></td></tr>
   </table>
   </form>
<?php
}

/* 新增期刊表單 */

function addvolume_form() {
   global $vjdb;
   $query = "SELECT ALIAS from $vjdb->volumes ORDER BY CREATE_DATE DESC LIMIT 1";
   $last = $vjdb->get_var($query);
   if((int)$last) {
      $next = (int)$last + 1;
   }
?>
   
   <form method="post" action="volumes.php">
   <h2>新增一期期刊</h2>
   <p>請問您要新增的期刊是第幾期？</p>
   <p><small>您可以輸入「第 1 期」、「第 12 期」，或是，如果這一期為合刊時，可以輸入第「 1-2」期等。</small></p>
   &nbsp;第 <input type="text" name="volume" value="<?php echo $next ?>" style="text-align: center;"/> 期 &nbsp;
   <input type="submit" value="新增這一期的期刊"/>
   <input type="hidden" name="action" value="add-volume"/>
   </form>
<?php
   if($last) {
      echo "<p>補充：您最近一次新增期刊的期數是：第 $last 期</p>";
   }

}

function addemail_form($email = "", $name = "") {
   echo '<form method="post" action="subscribers.php">';
   echo '<ul>';
   echo '<li><label for="email">信箱</label>：<input type="text" id="email" name="email" value="'.$email.'" size="50"/>（必須輸入） </li>';
   echo '<li><label for="name">姓名</label>：<input type="text" id="name" name="name" value="'.$name.'"/>（可以留空不填） </li>';
   echo '</ul>';
   echo '<input type="submit" value="新增訂戶"/>';
   echo '<input type="hidden" name="action" value="add-email"/>';
   echo '</form>';
}

function searchemail_form($keyword = "") {
   echo '<form method="post" action="subscriber-search.php">';
   echo '<label for="keyword">請輸入關鍵字</label>：<input type="text" id="keyword" name="keyword" value="'.$keyword.'" size="50"/>';
   echo '<input type="submit" value="搜尋"/>';
   echo '</form>';
}

/* 修改訂戶資料表單 */

function subscriber_form($email, $name, $refer, $id, $verified){
?>
   <form method="post">
<p><label for="email">信箱</label>：<input type="text" id="email" name="email" value="<?php echo $email ?>" />
<p><label for="name">姓名</label>：<input type="text" id="name" name="name" value="<?php echo $name ?>" /></p>
   <p>是否已通過認證？
   <input type="radio" name="verified" value="1" id="v-1" <?php if($verified) {echo 'checked="checked"';} ?>/>
      <label for="v-1">是</label>
   <input type="radio" name="verified" value="0" id="v-2"  <?php if(!$verified) {echo 'checked="checked"';} ?> />
      <label for="v-2">否</label>
      </p>
   <input type="hidden" name="id" value="<?php echo $id; ?>"/>
   <input type="hidden" name="refer" value="<?php echo $refer ;?>"/>
   <p><input type="submit" name="submit" value="更新" /></p>
   </form>
<?
}

/* 資料上傳表單 */

function upload_form($volumeid, $postid) {
   global $_POST, $vjdb, $ajax;

   echo '<form enctype="multipart/form-data" action="upload-submit.php" method="post">';
   if($volumeid) {
      $query = "SELECT ID FROM $vjdb->volumes WHERE ID='$volumeid'";
      $id  = $vjdb->get_var($query);
      if(!$id)  {
	 echo "<p>沒有這一期的期刊，不可以上傳照片！</p>";
	 return;
      } else {
	 $query = "SELECT ID FROM $vjdb->images WHERE VOLUMEID='$volumeid'";
	 $result  = $vjdb->get_results($query,ARRAY_A);
	 if($result) {
	    echo "<p>您已經上傳了這一期期刊的主題照片，請不要重複上傳！</p>";
	    echo "<p><a href=\"volume-info.php?id=".$volumeid."\">回到期刊設定頁面</a></p>";
	    return;
	 }

	 $query = "SELECT ALIAS FROM $vjdb->volumes WHERE ID='$volumeid'";
	 $alias  = $vjdb->get_var($query);
	 echo "<p>您正要上傳第 ".$alias." 期期刊的主題照片…</p>";
	 echo '<input type="hidden" name="volumeid" value="'.$volumeid.'" />';
      }
   }
   if($postid) {
      $query = "SELECT ID FROM $vjdb->post WHERE ID='$postid'";
      $id  = $vjdb->get_var($query);
      if(!$id)  {
	 echo "<p>沒有這一篇文章，不可以上傳照片！</p>";
	 return;
      } else {
	 $query = "SELECT TOPIC FROM $vjdb->post WHERE ID='$postid'";
	 $title  = $vjdb->get_var($query);
	 echo "<p>您正要上傳《".$title."》這篇文章的圖片…</p>";
	 echo '<input type="hidden" name="postid" value="'.$postid.'" />';
      }
   }
   if($ajax) { echo'<input type="hidden" name="ajax" value="1">'; }
   echo '<p>請選擇您要上傳的檔案: <input name="userfile" class="myinput" type="file"';
   if($_POST['userfile']) {echo 'value="'.$_POST['userfile'].'"'; }
   echo '></p>';
   echo '<p>請輸入扼要的圖說: <input name="tagline" type="text" class="myinput" size="50" value="'.$_POST[description].'"></p>';
   echo '<input type="hidden" name="action" value="image" />';
   echo '<input type="submit" value="上傳檔案">';
   echo '</form>';
}

function attach_form($postid) {
   global $vjdb, $_POST, $ajax;
   echo '<form enctype="multipart/form-data" action="upload-submit.php" method="post">';
   if($postid) {
      $query = "SELECT ID FROM $vjdb->post WHERE ID='$postid'";
      $id  = $vjdb->get_var($query);
      if(!$id)  {
	 echo "<p>沒有這一篇文章，不可以上傳附件！</p>";
	 return;
      } else {
	 $query = "SELECT TOPIC FROM $vjdb->post WHERE ID='$postid'";
	 $title  = $vjdb->get_var($query);
	 echo "<p>您正要上傳《".$title."》這篇文章的附件…</p>";
	 echo '<input type="hidden" name="postid" value="'.$postid.'" />';
      }
   }
   if($ajax) { echo'<input type="hidden" name="ajax" value="1">';  }
   echo '<p>請選擇您要上傳的檔案: <input name="userfile" class="myinput" type="file"';
   if($_POST['userfile']) {echo 'value="'.$_POST['userfile'].'"'; }
   echo '></p>';
   echo '<p>請輸入扼要的檔案說明: <input name="tagline" type="text" class="myinput" size="50" value="'.$_POST[description].'"></p>';
   echo '<input type="hidden" name="action" value="simple" />';
   echo '<input type="submit" value="上傳檔案" />';
   echo '</form>';
}

?>
