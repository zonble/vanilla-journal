<?php

class post
{
   var $err_msg = "";
   var $post_id = 0;
   var $post_topic = "";
   var $post_topic_ext = "";
   var $post_body = "";
   var $post_abstract = "";
   var $post_author = "";
   var $post_author_desc = "";
   var $post_niceauthor = "";
   var $post_htmlauthor = "";
   var $post_keyword = "";
   var $post_nicekeyword = "";
   var $post_htmlkeyword = "";
   var $post_permalink = "";
   var $post_editlink = "";
   var $post_backlink = "";
   var $post_order = "";
   var $post_date = "";
   var $post_cat = 0;
   var $post_catname = "";
   var $post_catdesc = "";
   var $post_vol = 0;
   var $post_vol_alias = "";
   var $post_vol_aliasext = "";
   var $post_vol_date = "";
   var $post_cite_apa;
   var $post_cite_mla;
   var $display;
   var $published;

   function post($id = 0, $single = 1)
   {
      $this->query_post($id);
      if ($single) {
         $this->update_vol();
      }
      if ($this->check_perm()) {
         $this->handle_link();
         $this->handle_author();
         $this->handle_keyword();
         $this->handle_cite();
         $this->handle_title();
         $this->get_image();
         $this->get_attaches();
      }
   }

   function query_post($id)
   {
      global $vjdb;
      $query = "SELECT * FROM " . $vjdb->post . " WHERE ID='$id' LIMIT 1";
      $result = $vjdb->get_row($query, ARRAY_A);
      if ($result) {
         $this->get_postinfo($result);
      }
   }

   function get_postinfo($result)
   {
      if ($result) {
         $this->post_id = $result['ID'];
         $this->post_topic = $result['TOPIC'];
         $this->post_topic_ext = $result['TOPIC_EXT'];
         $this->post_body = $result['BODY'];
         $this->post_abstract = $result['ABSTRACT'];
         $this->post_author = $result['AUTHOR'];
         $this->post_author_desc = $result['AUTHOR_DESC'];
         $this->post_keyword = $result['KEYWORD'];
         $this->display = $result['DISPLAY'];
         $this->post_cat = $result['CAT'];
         $this->post_catname = cat_name($result['CAT']);
         $this->post_catdesc = cat_desc($result['CAT']);
         $this->post_order = $result['POST_ORDER'];
         $this->post_date = mysql2date('U', $result['POST_DATE']);
         $this->post_vol = $result['VOLUME'];
      }
   }

   function update_vol()
   {
      global $volume;
      $volume = new volume($this->post_vol);
      $this->published = $volume->volume_published;
      $this->post_vol_date = $volume->volume_date;
      $this->post_vol_alias = $volume->volume_alias;
      $this->post_vol_aliasext = $volume->volume_aliasext;
   }

   function get_image()
   {
      global $vjdb;
      $query = "SELECT * FROM " . $vjdb->images . " WHERE POSTID='" . $this->post_id . "' AND DISPLAY='1' ORDER BY IMAGE_ORDER ASC, ID DESC";
      $results = $vjdb->get_results($query, ARRAY_A);
      if ($results) {
         $this->post_images = $results;
      }
   }

   function get_attaches()
   {
      global $vjdb;
      $query = "SELECT * FROM " . $vjdb->attaches . " WHERE POSTID='" . $this->post_id . "' AND DISPLAY='1'";
      $results = $vjdb->get_results($query, ARRAY_A);
      if ($results) {
         $this->post_attaches = $results;
      }
   }

   function check_perm()
   {
      if (!$this->post_exist()) {
         $this->err_msg = "對不起，系統中沒有這篇文章！";
         return false;
      } else if (!is_logined()) {
         if (!$this->display) {
            $this->err_msg = "對不起，因為某些原因，這篇文章目前不能公開！";
            return false;
         }
         if (!$this->published) {
            $this->err_msg = "對不起，這期期刊尚未出版，請稍後再試！";
            return false;
         }
      }
      return true;
   }

   function error()
   {
      if ($this->err_msg) {
         vj_die("<h2>" . $this->err_msg . "</h2>", "讀取文章時發生錯誤！");
      }
   }

   function handle_link()
   {
      global $config;
      $this->post_permalink = $config['post_viewlink'] . $this->post_id;
      $this->post_editlink = $config['post_editlink'] . $this->post_id;
      $this->post_backlink = $config['vol_viewlink'] . $this->post_vol;
      $this->post_endnotelink = $config['post_endnotelink'] . $this->post_id;
   }

   function handle_author()
   {
      if ($this->post_author) {
         $this->post_niceauthor = str_replace(',', "、", $this->post_author);
      } else {
         $this->post_niceauthor = vjinfo('title');
      }

      if ($this->post_author) {
         global $config;
         $authors = explode(",", $this->post_author);
         $count = count($authors);
         $i = 1;
         foreach ($authors as $author) {
            $author = trim($author);
            if ($author) {
               $this->post_htmlauthor .= '<a href="' . $config['authorsearchurl'] . urlencode($author) . '">' . $author . '</a>';
               if ($i++ < $count) {
                  $this->post_htmlauthor .= '、';
               }
            }
         }
      }
   }

   function handle_keyword()
   {
      if ($this->post_keyword) {
         $this->post_nicekeyword = str_replace(',', "、", $this->post_keyword);
      }

      if ($this->post_keyword) {
         global $config;
         $keywords = explode(",", $this->post_keyword);
         $count = count($keywords);
         $i = 1;
         foreach ($keywords as $keyword) {
            $keyword = trim($keyword);
            if ($keyword) {
               $this->post_htmlkeyword .= '<a href="' . $config['keywordsearchurl'] . urlencode($keyword) . '">' . $keyword . '</a>';
               if ($i++ < $count) {
                  $this->post_htmlkeyword .= '、';
               }
            }
         }
      }
   }

   function handle_cite()
   {
      global $config;
      $datestr = date("Y 年 n 月 j 日", $this->post_vol_date);
      $nowstr = date("Y 年 n 月 j 日", time());
      $yearstr = date("Y", $this->post_vol_date);

      $this->post_cite_apa = $this->post_niceauthor . "（" . $datestr . "），" . "〈" . $this->post_topic . "〉。《" . vjinfo('title') . "》，" . $nowstr . "取自：" . $this->post_permalink;
      $this->post_cite_mla = $this->post_niceauthor . "，〈" . $this->post_topic . "〉。《" . vjinfo('title') . "》第 " . $this->post_vol_alias . " 期（" . $yearstr . "），取自：" . $this->post_permalink;
   }

   function handle_title()
   {
      append_title($this->post_topic);
      set_volalias("第 " . $this->post_vol_alias . " 期" . $this->post_vol_aliasext);
      $datestr = date("Y 年 n 月 j 日", $this->post_vol_date);
      set_voldate($datestr);
   }

   function cite_apa()
   {
      return $this->post_cite_apa;
   }

   function cite_mla()
   {
      return $this->post_cite_mla;
   }

   function post_exist()
   {
      return $this->post_id;
   }

   function cat_link()
   {
      global $volume;
      $excs = array($this->post_id);
      $str = $volume->get_cat_list($this->post_cat, 0, $excs);
      return $str;
   }
}

function realtive_link($before = "", $after = "")
{
   global $post;
   $links = $post->cat_link();
   if ($links) {
      echo $before;
      echo $links;
      echo $after;
   }
   return;
}

function post_topic($before = "", $after = "")
{
   global $post;
   if ($post->post_topic) {
      echo $before;
      echo $post->post_topic;
      echo $after;
   }
   return;
}

function post_topic_ext($before = "", $after = "")
{
   global $post;
   if ($post->post_topic_ext) {
      echo $before;
      echo $post->post_topic_ext;
      echo $after;
   }
   return;
}

function post_author_plain()
{
   global $post;
   if ($post->post_author) {
      echo $post->post_author;
   } else {
      info('publisher');
   }
   return;
}

function post_author($before = "", $after = "")
{
   global $post;
   if ($post->post_htmlauthor) {
      echo $before;
      echo $post->post_htmlauthor;
      echo $after;
   }
   return;
}

function post_author_desc($before = "", $after = "")
{
   global $post;
   if ($post->post_author_desc) {
      echo $before;
      echo $post->post_author_desc;
      echo $after;
   }
   return;
}

function post_keyword_plain()
{
   global $post;
   echo $post->post_keyword;
   return;
}

function post_keyword($before = "", $after = "")
{
   global $post;
   if ($post->post_htmlkeyword) {
      echo $before;
      echo $post->post_htmlkeyword;
      echo $after;
   }
   return;
}

function post_link()
{
   global $post;
   return $post->post_permalink;
}

function post_permalink()
{
   global $post;
   echo $post->post_permalink;
   return;
}

function post_endnotelink()
{
   global $post;
   echo $post->post_endnotelink;
   return;
}

function post_body($before = "", $after = "")
{
   global $post;
   if ($post->post_body) {
      echo $before;
      echo $post->post_body;
      echo $after;
   }
   return;
}

function post_abstract($before = "", $after = "")
{
   global $post;
   if ($post->post_abstract) {
      echo $before;
      echo $post->post_abstract;
      echo $after;
   }
   return;
}

function post_cat($before = "", $after = "")
{
   global $post;
   if ($post->post_catname) {
      echo $before;
      echo $post->post_catname;
      echo $after;
   }
   return;
}

function post_backlink($text = "")
{
   global $post;
   $str = '<a href="' . $post->post_backlink . '">';
   if ($text) {
      $str .= $text;
   } else {
      $str .= "« 回到" . vjinfo('title') . "第 " . $post->post_vol_alias . " 期";
   }
   $str .= '</a>';
   echo $str;
   return;
}

function post_year()
{
   global $post;
   $year = date("Y", $post->post_vol_date);
   echo $year;
   return;
}

function post_date()
{
   global $post;
   $year = date("n / j", $post->post_vol_date);
   echo $year;
   return;
}

function post_cite_apa()
{
   global $post;
   echo $post->post_cite_apa;
   return;
}

function post_cite_mla()
{
   global $post;
   echo $post->post_cite_mla;
   return;
}
?>