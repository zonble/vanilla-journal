<?php
include("admin.php");
$action = $_POST['action'];
if (empty($action)) {
	$action = $_GET['action'];
}
if ($action) {
	switch ($action) {
		case 'add-cat': //新增單元
			admin_header("新增單元");
			$cat_name = $_POST['cat_name'];
			$cat_desc = $_POST['cat_desc'];
			if (empty($cat_name)) {
				echo "<h2>錯誤！</h2>";
				echo "<p>您沒有輸入您要新增單元的名稱！請重新輸入！</p>";
				echo "<h2>新增單元</h2>";
				echo "<p>請問您要新增的單元的名稱是？<p>";
				cats_form($cat_name, $cat_desc, "新增單元", "add-cat");
			} else {
				$query = "INSERT INTO $vjdb->cat (CAT_NAME, CAT_DESC) ";
				$query .= "VALUES ('$cat_name', '$cat_desc')";
				// echo $query;
				$vjdb->query($query);
				echo "<h2>新增單元完成！</h2>";
				echo "<p><a href=\"cats.php\">回到文章單元設定</a></p>";
			}
			admin_footer();
			break;
		case 'delete': //刪除單元
			$id = $_GET['id'];
			$query = "SELECT ID FROM $vjdb->cat WHERE ID='$id'";
			$cat = $vjdb->get_var($query);
			if ($cat) {
				admin_header("刪除單元");
				$query = "SELECT COUNT(ID) FROM $vjdb->post WHERE CAT='$id'";
				$postcount = $vjdb->get_var($query);
				echo "<h2>刪除單元：「" . cat_name($id) . "」</h2>\n";
				echo "<p>目前共有 " . (int) $postcount . " 篇屬於這個單元的文章。您確定要刪除嗎？</p>";
				echo '<form method="post" action="cats.php">';
				echo '<input type="submit" value="確定刪除" />';
				echo '<input type="hidden" name="action" value="deleteexec" />';
				echo '<input type="hidden" name="id" value="' . $id . '" />';
				if ($postcount) { // 如果單元下有文章，可不能隨便亂刪啊！
					echo '<p>刪除單元後，將目前屬於這個單元的文章的單元設定為：';
					echo '<select name="newcat">';
					$query = "SELECT ID, CAT_NAME FROM $vjdb->cat ORDER BY ID ASC;";
					$mycats = $vjdb->get_results($query, ARRAY_A);
					foreach ($mycats as $mycat) {
						if ($mycat['ID'] != $id) {
							echo "<option value=\"" . $mycat['ID'] . "\"";
							echo ">" . $mycat['CAT_NAME'] . "</option>";
						}
					}
					echo '</select></p>';
				}
				echo '</form>';
				$refer = $_SERVER['HTTP_REFERER'];
				echo '<p><a href="' . $refer . '">不刪除，回到前一頁</a></p>';
			} else {
				$str = "<h2>錯誤</h2>\n";
				$str .= "<p>找不到您想要刪除的單元！</p>";
				$str .= "<p><a href=\"cats.php\">回到文章單元設定</a></p>";
				admin_die($str, "刪除單元時發生錯誤");
			}
			break;
		case 'deleteexec': //執行刪除
			$id = $_POST['id'];
			$query = "SELECT ID FROM $vjdb->cat WHERE ID='$id'";
			$cat = $vjdb->get_var($query);
			$newcat = $_POST['newcat'];
			if ($cat) {
				$query = "DELETE FROM $vjdb->cat WHERE ID='$id'";
				$cat = $vjdb->get_var($query);
				if ($newcat) {
					$query = "UPDATE $vjdb->post SET CAT='$newcat' WHERE CAT='$id'";
					$cat = $vjdb->query($query);
				}
				header("Location: cats.php");
			} else {
				$str = "<h2>錯誤</h2>\n";
				$str .= "<p>找不到您想要刪除的單元！</p>";
				$str .= "<p><a href=\"cats.php\">回到文章單元設定</a></p>";
				admin_die($str, "刪除單元時發生錯誤");
			}

			break;
		case 'edit': //編輯單元
			$id = $_GET['id'];
			$query = "SELECT * FROM $vjdb->cat WHERE ID='$id'";
			$cat = $vjdb->get_row($query, ARRAY_A);
			if ($cat) {
				$cat_name = $cat['CAT_NAME'];
				$cat_desc = $cat['CAT_DESC'];
				admin_header("修改單元設定");
				?>
<h2>修改單元</h2>
<p>請問您要將單元的名稱修改為？
<p>
    <?php
					cats_form($cat_name, $cat_desc, "修改單元", "update", $id);
			} else {
				$str = "<h2>錯誤</h2>\n";
				$str .= "<p>找不到您想要修改的單元！</p>";
				$str .= "<p><a href=\"cats.php\">回到文章單元設定</a></p>";
				admin_die($str, "更新單元資訊時發生錯誤");
			}
			break;
		case 'update': //更新單元
			$id = $_POST['id'];
			$query = "SELECT * FROM $vjdb->cat WHERE ID='$id'";
			$cat = $vjdb->get_row($query, ARRAY_A);
			if ($cat) {
				$cat_name = $_POST['cat_name'];
				$cat_desc = $_POST['cat_desc'];
				$query = "UPDATE $vjdb->cat SET CAT_NAME = '$cat_name', CAT_DESC = '$cat_desc' WHERE ID ='$id'";
				$vjdb->get_var($query);
				header("Location: cats.php");
			} else {
				$str = "<h2>錯誤</h2>\n";
				$str .= "<p>找不到您想要修改的單元！</p>";
				$str .= "<p><a href=\"cats.php\">回到文章單元設定</a></p>";
				admin_die($str, "更新單元資訊時發生錯誤");
			}
			break;
		default:
			break;
	}
} else {
	admin_header("文章單元管理");
	?>

<div class="wrap">
    <h2>文章單元設定</h2>
    <p>目前資料庫中的文章單元表列如下，您可以修改、刪除或新增文章單元。</p>
    <?php cat_table(); ?>
</div>

<div class="wrap">
    <h2>新增單元</h2>
    <p>請問您要新增的單元是？
    <p>
        <?php cats_form("", "", "新增單元", "add-cat"); ?>
</div>

<?php
}
admin_footer();
?>