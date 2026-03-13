<?php
include('admin.php');

function import_form($url = "", $volume)
{
   if (!$url) {
      $url = "http://";
   }
   echo '<form action="import.php" method="post">';
   echo ' <p><label for="url">RSS 網址：</label>';
   echo '<input type="text" name="url" value="' . $url . '" size="50" /></p>';
   echo '<input type="hidden" name="volume" value="' . $volume . '" /></p>';
   echo '<p><input type="submit" name="submit" value="匯入" /></p>';
   echo '</form>';
}

?>