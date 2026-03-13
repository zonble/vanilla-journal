<?php
if ($_GET['ajax']) {
   include("admin.php");
   header("Content-type: text/html; charset=utf-8");
   $opt = $_GET['opt'];
   $keyword = $_GET['keyword'];
   subscribe_table($opt, $keyword);
}
?>