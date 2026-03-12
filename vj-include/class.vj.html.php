<?php

/* Vanilla Journal
 * class.vj.html.php
 * Weizhong Yang
 */

class html {
   var $html_title = "";
   var $html_title_append = "";
   var $html_volalias = "";
   var $html_voldate = "";
   var $html_extra =""; 

   function html(){
   }

   function handle_title(){
      if($this->html_title_append) {
	 $this->html_title = vjinfo('title')." » ".$this->html_title_append;
      } else {
	 $this->html_title = vjinfo('title');
      }
      return $this->html_title;
   }

   function get_volalias($str="") {
      $this->html_volalias = $str;
      return true;
   }

   function get_voldate($str="") {
      $this->html_voldate = $str;
      return true;
   }

   function vj_header($title = ""){
      global $is_email, $config, $volume;
      if($title) {
	 append_title($title);
      }
      header("Content-type: text/html; charset=utf-8");
      if(file_exists($config['theme_path']."header.php")) {
	 include($config['theme_path']."header.php");
      }
   }

   function vj_footer(){
      global $config;      
      if(file_exists($config['theme_path']."footer.php")) {
	 include($config['theme_path']."footer.php");
      }
      die();
   }

   function vj_toc(){
      global $config;
      global $volume;
      if(file_exists($config['theme_path']."toc.php")) {
	 include($config['theme_path']."toc.php");
      }
   } 

   function vj_post(){
      global $config;      
      if(file_exists($config['theme_path']."post.php")) {
	 include($config['theme_path']."post.php");
      }
   }

   function vj_file($file){
      global $config;
      if(file_exists($config['theme_path'].$file)) {
	 include($config['theme_path'].$file);
      }
   }        

   function vj_archive() {
      global $vj;
      $volumes = $vj->published_volumes;
      if(!$volumes) return;
      foreach($volumes as $volume) {
	 echo "<link rel=\"archives\" title=\"第 ".$volume['ALIAS']."期 ".$volume['TOPIC']."\" href=\"".vjinfo('url')."index.php?volume=".$volume['ID']."\" /> \n";
      }
   }

   function vj_archive_nav() {
      global $vj;
      $volumes = $vj->published_volumes;
      if(!$volumes) return;
      echo "<ul>\n";
      foreach($volumes as $volume) {
	 echo "<li><a title=\"第 ".$volume['ALIAS']." 期 ".$volume['TOPIC']."\" href=\"".vjinfo('url')."index.php?volume=".$volume['ID']."\">第 ".$volume['ALIAS']." 期 ".$volume['TOPIC']."</a></li>\n";
      }
      echo "</ul>\n";
   }
}

function show_archive() {
   global $html;
   return $html->vj_archive();
}

function show_archive_nav() {
   global $html;
   return $html->vj_archive_nav();
}

function set_volalias($str) {
   global $html;
   return $html->get_volalias($str);
}

function set_voldate($str) {
   global $html;
   return $html->get_voldate($str);
}

function vj_header($title = ""){
   global $html;
   return $html->vj_header($title);
}

function vj_footer(){
   global $html;
   return $html->vj_footer();
   die();
}

function vj_toc(){
   global $html;
   return $html->vj_toc();
} 

function vj_post(){
   global $html;
   return $html->vj_post();
}

function vj_file($file){
   global $html;
   return $html->vj_file($file);
}   

function vj_die($str="", $title=""){
   append_title($title);
   vj_header();
   echo $str;
   vj_footer();
}

function append_title($title=""){
   global $html;
   $html->html_title_append = $title;
}

function vj_title(){
   global $html;
   $html->handle_title();
   echo $html->html_title;
}

function vj_volalias($before="", $after=""){
   global $html;
   if(!$html->html_volalias) return;
   if($before) { echo $before; }
   echo $html->html_volalias;
   if($after) { echo $after; }
}

function vj_voldate($before="", $after=""){
   global $html;
   if(!$html->html_voldate) return;
   if($before) { echo $before; }
   echo $html->html_voldate;
   if($after) { echo $after; }
}

function vj_adminbar() {
   global $_GET;
   if($_GET['ajax']) return;
   echo '<div style="background: #AAA; color: #FFF; font-size: 10pt; position: absolute; top: 0; left: 0px; width: 100%; padding: 0; text-align: right; height: 20px;">';
   echo '<span style="float: left;">您已經登入發報管理介面了。</span>';
   echo '<a style="color: #FFF;" href="'.vjinfo('url').'vj-admin/">前往發報管理介面</a> | ';
   echo '<a style="color: #FFF;" href="'.vjinfo('url').'vj-admin/logout.php">登出</a>';
   echo '</div>';
}

?>
