<?php
   include("admin.php");
   if($_POST['volume']) {
      $volume=$_POST['volume'];
   } else {
      $volume=$_GET['volume'];
   }
   $volinfo = $vjdb->get_row("SELECT * FROM $vjdb->volumes WHERE ID='$volume'",ARRAY_A);
   if(empty($volinfo)) {
      $str = "<h2>錯誤！</h2>\n";
      $str .= "<p>系統中目前沒有您所指定代號的期刊資料！</p>";
      admin_die($str, "在管理文章時發生錯誤");
   } else {
   $pagetitle ="管理《".vjinfo('title')."》第 ".$volinfo['ALIAS']." 期的文章";
   admin_header();

?>

<div class="wrap">
<?php $pagetitle ="管理《".vjinfo('title')."》第 ".$volinfo['ALIAS']." 期的文章"; ?>
<h2><?php echo $pagetitle ?></h2>

<div class="tool">
您也可以：
<a href="volume-info.php?id=<?php echo $volume?>" onclick="return vj.util.exitconfirm();">設定本期期刊</a> |
<a href="post-add.php?volume=<?php echo $volume?>" onclick="return vj.util.exitconfirm();">增加本期內容</a> | 
<a href="import.php?volume=<?php echo $volume?>" onclick="return vj.util.exitconfirm();">從 RSS 匯入文章到本期中</a> | 
<a href="../index.php?volume=<?php echo $volume?>" id="viewpage">查看本期網頁</a> |
<a href="volumes.php" onclick="return vj.util.exitconfirm();">回到期數文章總覽</a>
</div>

<?php
   if($_POST['submit']) {
      foreach($_POST as $key => $value){
	 if(strstr($key, 'order-')) {
	    $order = str_replace('order-','', $key);
	    if($order) {
	       $query = "UPDATE $vjdb->post SET POST_ORDER = $value WHERE ID = $order";
	       // echo $query."<br />";
	       $vjdb->query($query);
	    }
	 } 
      }
      echo '<div id="msg">文章順序更新完畢！</div>';
   }
?>

<form action="volume-edit.php?volume=<?php echo $volume ?>" method="post">
<p>本期文章列表如下。</p>
   <script type="text/javascript">
   document.write('<p class="support">您可以在此調整文章的次序，除了用下拉選單調整外，還可以直接使用拖拉的方式調整，（不過，某些瀏覽器—例如 MacOS X 上的 Safari 瀏覽器，目前還無法正確使用拖拉功能）；調整完之後請記得按一下「更新文章排列順序」。</p>');
   </script>
<p><input type="submit" name="submit" value="更新文章排列順序" /></p>
<input type="hidden" name="volume" value="<?php echo $volume ?>" />
<?php
   post_table($volume);
?>
</form>
</div>

<?php 
   }
   admin_footer();
?>
