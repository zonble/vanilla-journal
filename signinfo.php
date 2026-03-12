<?php 
   include("vj-header.php");
   $vj->thispage = "subscribe";
   $id = $_GET['id'];
   $hash = $_GET['hash'];
   $action = $_GET['action'];
   $query = "SELECT HASH FROM $vjdb->subscribers WHERE ID = '$id'";
   $oldhash = $vjdb->get_var($query);

   if($id && $hash && $oldhash && $action) {
      if($hash != $oldhash) {
	 $str = "<h2>錯誤</h2>";
	 $str .= "<p>資料庫中並沒有您所指定的訂閱或退訂資料</p>";
	 vj_die($str, "訂閱或退訂時發生錯誤！");
      } else {
	 if($action == "verify") {
	    $query = "UPDATE $vjdb->subscribers SET VERIFIED = '1' WHERE ID='$id' ";
	    if($vjdb->query($query)) {
	       $str = "<h2>訂閱認證成功！</h2>";
	       $str .= "<p>您已經完成訂閱，以後系統會寄給您最新的當期電子報。</p>";
	       vj_die($str, "訂閱成功！");
	    } else {
	       vj_die("<p>訂閱時發生系統錯誤</p>", "訂閱失敗！");
	    }
	 } else if ($action == "reject") {
	    $query = "DELETE FROM $vjdb->subscribers WHERE ID='$id' ";
	    if($vjdb->query($query)) {
	       $str = "<h2>退訂成功！</h2>";
	       $str .= "<p>您已經完成退訂，以後系統不會再寄給您電子報了。</p>";
	       vj_die($str, "退訂成功！");
	    } else {
	       vj_die("<p>退訂時發生系統錯誤</p>", "退訂失敗！");
	    }
	 }
      }
   }
   header("Location: ".vjinfo('url'));
?>
