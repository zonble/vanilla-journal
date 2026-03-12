<?php
   include("admin.php");

   $action = $_POST['action'];
   if($action == 'edit'){
      foreach ($_POST as $key => $value){
         if($key == 'action') continue;
         $$key = $value; //字串直接轉成變數名稱 XD
         if($key == 'id') continue;
	 if($key == 'body') {
	    $value=cleantag($value);
	 }
         $table = strtoupper($key);
	 $set .= " ".$table." = '".$value."',";
      }
      $query = "SELECT ID FROM $vjdb->post WHERE ID= $id";
      $id = $vjdb->get_var($query);
      if($id) {
	 $query = "UPDATE $vjdb->post SET ".$set.") WHERE ID= $id";
	 $query = str_replace(",)", "", $query);
	 if(empty($topic)) {
	    $err_str = "請輸入標題！";
	 } 
	 if(empty($err_str)){
	    $vjdb->query($query);
	    header("Location: volume-edit.php?volume=$volume");
	 }
      } else {
	 admin_error("系統中沒有您指定的 ID ！");
      }
   } else {

      $id = $_GET['id'];
      $query = "SELECT ID FROM $vjdb->post WHERE ID='$id';";
      $id = $vjdb->get_var($query);
      if(!$id) {
	 admin_error("系統沒有您想要編輯的文章，請正確指定您要編輯文章的代號！");
      } else {
	 $query = "SELECT * FROM $vjdb->post WHERE ID='$id';";
	 $values = $vjdb->get_row($query);
	 foreach($values as $key=>$value){
	    $key = strtolower($key);
	    $$key = $value;
	 }
	 $wysiwyg = 1;
	 admin_header("編輯文章");
	 $actionlabel = "完成編輯！";
	 $target = "post-edit.php";
	 $action = "edit";
	 echo "<div>";
	 echo "<h2>編輯文章</h2>";
	 echo "<a name=\"part-1\"></a>";
	 include "post-form.php";
?>
   <a name="images"></a>
   <div id="post_images"> 
   <?php include_once("upload-list.php") ; ?>
   </div>
   <a name="attach"></a>
   <div id="post_attach"> 
   <?php include_once("attach-list.php") ; ?>
   </div>
   </div>
      <?
      }
   }
   admin_footer();
?>
