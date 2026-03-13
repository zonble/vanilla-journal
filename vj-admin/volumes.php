<?php
include("admin.php");
$action = $_POST['action'];
if ($action) {
	switch ($action) {
		case 'add-volume': //新增期數
			$volume = $_POST['volume'];
			if (empty($volume)) {
				admin_header("新增期刊時發生錯誤");
				echo "<h2>錯誤！</h2>";
				echo "<p>您沒有輸入您要新增的是第幾期！請重新輸入！</p>";
				addvolume_form();
				admin_footer();
			} else {
				$time = date("Y-m-d H:i:s");
				$query = "INSERT INTO $vjdb->volumes (ALIAS, CREATE_DATE) ";
				$query .= "VALUES ('$volume', '$time')";
				if ($vjdb->query($query)) {
					admin_header("新增完成！");
					echo "<h2>新增完成！</h2>";
					echo "<p>您現在可以對這一期的期刊內容，進行進一步的設定。</p>";
					$query = "SELECT ID FROM $vjdb->volumes ORDER BY ID DESC LIMIT 1";
					$id = $vjdb->get_var($query);
					echo "<p><a href=\"volume-info.php?id=$id\">繼續設定</a></p>";
					admin_footer();
				} else {
					admin_header("新增期刊時發生錯誤");
					echo "<h2>新增期數時發生錯誤！</h2>";
					echo "<p>在新增期數時發生錯誤，無法新增。</p>";
					admin_footer();
				}
			}
			break;
		default:
			break;
	}
} else {
	admin_header("期刊期數文章維護");
	?>

<div class="wrap">
    <h2>期刊期數文章維護</h2>
    <p>目前資料庫中的各期期刊表列如下，您可以在此設定各期期刊的資料、上傳主題圖片，以及添加各期中的文章內容。</p>
    <?php volume_table(); ?>
</div>

<div class="wrap">
    <?php addvolume_form(); ?>
</div>

<?php
}
admin_footer();
?>