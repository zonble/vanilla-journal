<?php

/* Vanilla Journal
 * vj.funtion.php
 * Weizhong Yang
 */

function print_z($obj)
{
   echo "<pre>";
   print_r($obj);
   echo "</pre>";
}

function remove_html($content)
{
   $content = preg_replace("/<(.*?)>/", "", $content);
   $content = str_replace("&", "&amp;", $content);
   return $content;
}

function cat_postlist($cat)
{
   global $this_volume, $vjdb;
   global $config;

   if (!$this_volume)
      return;
   if (is_logined()) {
      $query = "SELECT ID, TOPIC, AUTHOR, DISPLAY FROM post WHERE CAT='$cat' AND VOLUME = '$this_volume' ORDER BY POST_ORDER ASC;";
   } else {
      $query = "SELECT ID, TOPIC, AUTHOR, DISPLAY FROM post WHERE CAT='$cat' AND VOLUME = '$this_volume' AND DISPLAY='1' ORDER BY POST_ORDER ASC;";
   }
   $posts = $vjdb->get_results($query, ARRAY_A);
   if ($posts) {
      $str = '<ul class="linklist">';
      foreach ($posts as $post) {
         $str .= "<li><a href=\"" . $config['post_viewlink'] . $post['ID'] . "\">" . $post['TOPIC'] . "</a>";
         if ($post['AUTHOR']) {
            $str .= " / " . $post['AUTHOR'];
         }
         if (!$post['DISPLAY']) {
            $str .= " [不公開]";
         }
         $str .= "</li>";
      }
      $str .= "</ul>";
   }
   return $str;
}

function check_email_address($email)
{
   if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
      return false;
   }
   $email_array = explode("@", $email);
   $local_array = explode(".", $email_array[0]);
   for ($i = 0; $i < sizeof($local_array); $i++) {
      if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
         return false;
      }
   }
   if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
      // Check if domain is IP. If not, it should be valid domain name
      $domain_array = explode(".", $email_array[1]);
      if (sizeof($domain_array) < 2) {
         return false; // Not enough parts to domain
      }
      for ($i = 0; $i < sizeof($domain_array); $i++) {
         if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
            return false;
         }
      }
   }
   return true;
}

function mysql2date($dateformatstring, $mysqlstring, $translate = true)
{
   global $month, $weekday, $month_abbrev, $weekday_abbrev;
   $m = $mysqlstring;
   if (empty($m)) {
      return false;
   }
   $i = mktime(substr($m, 11, 2), substr($m, 14, 2), substr($m, 17, 2), substr($m, 5, 2), substr($m, 8, 2), substr($m, 0, 4));

   if (-1 == $i || false == $i)
      $i = 0;

   if (!empty($month) && !empty($weekday) && $translate) {
      $datemonth = $month[date('m', $i)];
      $datemonth_abbrev = $month_abbrev[$datemonth];
      $dateweekday = $weekday[date('w', $i)];
      $dateweekday_abbrev = $weekday_abbrev[$dateweekday];
      $dateformatstring = ' ' . $dateformatstring;
      $dateformatstring = preg_replace("/([^\\\])D/", "\${1}" . backslashit($dateweekday_abbrev), $dateformatstring);
      $dateformatstring = preg_replace("/([^\\\])F/", "\${1}" . backslashit($datemonth), $dateformatstring);
      $dateformatstring = preg_replace("/([^\\\])l/", "\${1}" . backslashit($dateweekday), $dateformatstring);
      $dateformatstring = preg_replace("/([^\\\])M/", "\${1}" . backslashit($datemonth_abbrev), $dateformatstring);

      $dateformatstring = substr($dateformatstring, 1, strlen($dateformatstring) - 1);
   }
   $j = @date($dateformatstring, $i);
   if (!$j) {
   }
   return $j;
}

function size_hum_read($size)
{
   /* Returns a human readable size */
   $i = 0;
   $iec = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
   while (($size / 1024) > 1) {
      $size = $size / 1024;
      $i++;
   }
   return substr($size, 0, strpos($size, '.') + 4) . $iec[$i];
}
?>