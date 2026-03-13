<?php

/* Vanilla Journal
 * class.vj.php
 * Weizhong Yang
 */


/* The class of categories */

class cat
{
   var $cat_vol_order = 0;
   var $cat_id = 0;
   var $cat_label = "";
   var $cat_name = "";
   var $cat_desc = "";
   var $cat_vol_desc = "";

   function cat_name()
   {
      return $this->cat_name;
   }

   function cat_label()
   {
      return $this->cat_label;
   }

   function cat_desc()
   {
      return $this->cat_desc;
   }

   function cat_vol_desc()
   {
      return $this->cat_vol_desc;
   }

   function cat_vol_order()
   {
      return $this->cat_vol_order;
   }
}

class vj
{
   var $info = array();
   var $cats = array();
   var $cat_count = 0;
   var $is_logined = 0;
   var $volumes = array();
   var $published_volumes = array();
   var $published_newst = 0;
   var $volume_newst = 0;
   var $thispage = "";

   function vj()
   {
      $this->query_info();
      $this->check_login();
      $this->query_cat();
      $this->query_published_volumes();
   }

   function query_info()
   {
      global $vjdb;
      $query = "SELECT * FROM $vjdb->infos";
      $keys = $vjdb->get_results($query, ARRAY_A);
      if (!$keys)
         return;
      foreach ($keys as $key) {
         $this->info[$key['KEY']] = $key['VALUE'];
      }
      $this->info['now_year'] = date("Y");
      $this->info['now_date'] = date("md");
   }

   function set_links()
   {
      global $config;
      $this->info['rss'] = $this->info['url'] . "rss.php";
      $this->info['url-admin'] = $this->info['url'] . "vj-admin/";
      $this->info['url-logout'] = $this->info['url'] . "vj-admin/logout.php";
      $this->info['url-js-shared'] = $this->info['url'] . "vj-script/";
      $this->info['url-js'] = $config['theme_url'] . "vj.js";
      $this->info['url-css-shared'] = $this->info['url'] . "vj-style/";
      $this->info['url-css-screen'] = $config['theme_url'] . "style.css";
      $this->info['url-css-print'] = $config['theme_url'] . "print.css";
   }

   function get_info($key = '')
   {
      return $this->info[$key];
   }

   function check_login()
   {
      global $_SESSION;
      session_start();
      $mypass = $_SESSION['password'];
      $password = $this->info['password'];
      if ($mypass == $password) {
         $this->is_logined = 1;
      } else {
         $this->is_logined = 0;
      }
      return true;
   }

   function is_logined()
   {
      return $this->is_logined;
   }

   function query_published_volumes()
   {
      global $vjdb;
      $query = "SELECT ID, ALIAS, ALIAS_EXT, TOPIC FROM " . $vjdb->volumes . " WHERE PUBLISHED = '1' ORDER BY CREATE_DATE DESC";
      $results = $vjdb->get_results($query, ARRAY_A);
      if ($results) {
         $i = 0;
         foreach ($results as $result) {
            $this->published_volumes[$i] = $result;
            $i++;
         }
         $this->published_newst = $this->published_volumes[0]['ID'];
      }
   }

   function query_all_volumes()
   {
      global $vjdb;
      $query = "SELECT ID, ALIAS, ALIAS_EXT, TOPIC FROM " . $vjdb->volumes . " ORDER BY CREATE_DATE DESC";
      $results = $vjdb->get_results($query, ARRAY_A);
      if ($results) {
         $i = 0;
         foreach ($results as $result) {
            $this->volumes[$i] = $result;
            $i++;
         }
      }
   }

   function query_newst_volume()
   {
      global $vjdb;
      $query = "SELECT ID FROM " . $vjdb->volumes . " ORDER BY CREATE_DATE DESC LIMIT 1";
      $this->volume_newst = $vjdb->get_var($query);
      return $this->volume_newst;
   }

   function published_newst()
   {
      return $this->published_newst;
   }

   function volume_newst()
   {
      return $this->volume_newst;
   }

   function cmp_published($volume)
   {
      foreach ($this->published_volumes as $published_volume) {
         if ($published_volume['ID'] == $volume) {
            return true;
         }
      }
      return false;
   }

   function query_cat()
   {
      global $vjdb;
      $query = "SELECT ID, CAT_NAME, CAT_DESC FROM " . $vjdb->cat;
      $result = $vjdb->get_results($query, ARRAY_A);
      if (!$result)
         return;
      foreach ($result as $key => $item) {
         $cat = new cat();
         $cat->cat_id = $item['ID'];
         $cat->cat_name = $item['CAT_NAME'];
         $cat->cat_desc = $item['CAT_DESC'];
         $cat->cat_label = "cat-" . $item['ID'];
         $this->cats[$cat->cat_label] = $cat;
      }
      $this->cat_count = count($this->cats);
   }

   // Sorting the categories

   function sort_cats()
   {
      sort($this->cats);
   }

   function get_cat_by_id($id = 0)
   {
      if ($this->cats["cat-" . $id]) {
         return $this->cats["cat-" . $id];
      } else {
         return false;
      }
   }
}

function vjinfo($key)
{
   global $vj;
   return $vj->get_info($key);
}

function info($key)
{
   global $vj;
   echo $vj->get_info($key);
}

function is_logined()
{
   global $vj;
   return $vj->is_logined;
}

function cmp_published($volume)
{
   global $vj;
   return $vj->cmp_published($volume);
}

function published_newst()
{
   global $vj;
   return $vj->published_newst;
}

function get_cat_by_id($id)
{
   global $vj;
   return $vj->get_cat_by_id($id);
}

function cat_name($id)
{
   $cat = get_cat_by_id($id);
   return $cat->cat_name;
}

function cat_desc($id)
{
   $cat = get_cat_by_id($id);
   return $cat->cat_desc;
}
?>