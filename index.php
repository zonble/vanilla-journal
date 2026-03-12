<?php 
include("vj-header.php");

if($_GET['id']) {
   include("vj-include/vj.viewpost.php");
} else if($_GET['file']) {
   include("vj-include/vj.viewfile.php");
} else {
   include("vj-include/vj.viewtoc.php");
}

?>
