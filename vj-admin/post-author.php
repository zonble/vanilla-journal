<?php
include("admin.php");
$author = $_POST['author'];
if(trim($author)) {
   $query = "SELECT DISTINCT AUTHOR FROM $vjdb->post WHERE AUTHOR LIKE '%$author%' ORDER BY AUTHOR ASC LIMIT 10";
} else {
   $query = "SELECT DISTINCT AUTHOR FROM $vjdb->post LIMIT 10";
}
$results = $vjdb->get_results($query, ARRAY_A);
$i = 0;
if($results) {
   header("Content-type: text/html; charset=utf-8");
   echo "<ul>";
   foreach($results as $result) {
      if($result['AUTHOR']) {
	 echo "<li id=\"author-$i\">".$result['AUTHOR']."</li>";
	 $i++;
      }
   }
   echo "</ul>";
}

?>
