<?php 
   include("vj-header.php");
   $vj->thispage = "archive";
   vj_header("前期期刊索引"); 
?>
<h2>期刊索引</h2>
<?php
   if(!is_logined()){
      $query = "SELECT ID, CREATE_DATE, TOPIC, ALIAS, PUBLISHED FROM $vjdb->volumes WHERE PUBLISHED = 1 ORDER BY CREATE_DATE DESC";
   } else {
      $query = "SELECT ID, CREATE_DATE, TOPIC, ALIAS, PUBLISHED FROM $vjdb->volumes ORDER BY CREATE_DATE DESC";
   }
   $volumes = $vjdb->get_results($query, ARRAY_A);

   if($volumes){
      echo "<table>";
      echo "<tr><th>期數</th><th>當期主題</th><th>出版日期</th></tr>";
      foreach($volumes as $volume){
	 $date = mysql2date("Y-m-d", $volume['CREATE_DATE']);
	 if($volume['TOPIC']) {
	    $subject = $volume['TOPIC'];
	 } else {
	    $subject = "&nbsp;";
	 }
	 echo "<tr><td style=\"width: 6em;\"><a href=\"index.php?volume=".$volume['ID']."\">第 ".$volume['ALIAS']." 期</a></td>";
	 echo "<td style=\"width: 20em;\"> ".$subject."</td>";
	 echo "<td> ".$date."出版";
	 if(!$volume['PUBLISHED']) {
	    echo " [尚未上線]";
	 }
	 echo "</td></tr>";
      }
      echo "</table>";
   }

?>
<?php vj_footer(); ?>
