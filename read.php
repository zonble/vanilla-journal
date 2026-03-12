<?php
/* 這個檔案的主要用途在於讀取遠端網頁，主要是在發報前預覽用 */
   $url = $_GET['url'];
   $url = urldecode($url);
   if($url) {
      $content = file_get_contents($url);
   }
   if($content) echo $content;
?>
