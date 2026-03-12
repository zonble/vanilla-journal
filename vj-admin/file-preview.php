<?php
   include("admin.php");

   $theme = $_GET['theme'];
   if(!$theme) {
      $theme = vjinfo('theme');
   }
   $config['theme'] = $theme;
   $config['theme_url'] = vjinfo('url')."theme/".$config['theme']."/";
   $config['theme_path'] = $config['basepath']."theme/".$config['theme']."/";
   $vj->set_links();
   include("../vj-include/vj.viewtoc.php");
?>