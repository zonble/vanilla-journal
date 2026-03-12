<?php
include("admin.php");
$chr = $_GET['author'];
$query = "SELECT DISTINCT AUTHOR_DESC FROM $vjdb->post WHERE AUTHOR LIKE '%$chr%' LIMIT 1";
$results = $vjdb->get_row($query, ARRAY_A);
if($results) {
   if($results['AUTHOR_DESC']) {
      echo $results['AUTHOR_DESC'];
   }
}

?>