<?php
$file = $_GET['file'];
$vj->thispage = $file;
vj_header();
vj_file($file);
vj_footer();
?>