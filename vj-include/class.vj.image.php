<?php

/* Vanilla Journal
 * class.vj.image.php
 * Weizhong Yang
 */

class image
{
   var $imageid = "";
   var $filename = "";
   var $thumbname = "";
   var $tagline = "";
   var $filelink = "";
   var $thumblink = "";
   var $filepath = "";
   var $thumbpath = "";
   var $editlink = "";
   var $dellink = "";
   var $w = 0; // width
   var $h = 0; // height
   var $tw = 0; // width of thumb
   var $th = 0; // width of height
   var $upload_date;

   function image($info = "")
   {
      if ($info) {
         $this->get_imageinfo($info);
      }
      $this->handle_thumb();
   }

   function handle_thumb()
   {
      if (($this->w == $this->tw) && ($this->h == $this->th) && vjinfo('thumb_max')) {
         if (($this->tw > vjinfo('thumb_max')) && ($this->th > vjinfo('thumb_max'))) {
            if ($this->tw > $this->th) {
               $this->th = (int) ($this->th * (vjinfo('thumb_max') / $this->tw));
               $this->tw = vjinfo('thumb_max');
            } else {
               $this->tw = (int) ($this->tw * (vjinfo('thumb_max') / $this->th));
               $this->th = (int) vjinfo('thumb_max');
            }
         }
      }
   }

   function get_imageinfo($info = "")
   {
      global $config;
      if ($info) {
         $this->imageid = $info['ID'];
         $this->filename = $info['FILENAME'];
         $this->thumbname = $info['THUMB'];
         $this->tagline = $info['TAGLINE'];
         $this->display = $info['DISPLAY'];
         $this->upload_date = mysql2date('U', $info['UPLOAD_DATE']);
         $size = unserialize($info['SIZE']);
         $this->w = $size['w'];
         $this->h = $size['h'];
         $this->tw = $size['tw'];
         $this->th = $size['th'];
         $this->filelink = $config['image_url'] . $info['FILEPATH'] . $info['FILENAME'];
         $this->thumblink = $config['image_url'] . $info['FILEPATH'] . $info['THUMB'];
         $this->filepath = $config['image_path'] . $info['FILEPATH'] . $info['FILENAME'];
         $this->thumbpath = $config['image_path'] . $info['FILEPATH'] . $info['THUMB'];
      }
   }

   function image_html($percent = 100, $pixel = 0, $class = "", $style = "")
   {
      if ($pixel) {
         $w = $pixel;
         $h = (int) ($this->h * (float) ($pixel / $this->w));
      } else if ($percent) {
         $w = (int) ($this->w * (float) ($percent / 100));
         $h = (int) ($this->h * (float) ($percent / 100));
      } else {
         $w = $this->w;
         $h = $this->h;
      }
      echo '<img src="' . $this->filelink . '" alt="' . $this->tagline . '" ';
      echo ' width="' . $w . '" height="' . $h . '" ';
      if ($class) {
         echo ' class="' . $class . '"';
      }
      if ($style) {
         echo ' style="' . $style . '"';
      }
      echo ' />';
   }

   function thumb_html($percent = 100, $class = "", $style = "")
   {
      $w = $this->tw;
      $h = $this->th;
      if ($percent) {
         $w = (int) ($this->tw * (float) ($percent / 100));
         $h = (int) ($this->th * (float) ($percent / 100));
      }
      echo '<img src="' . $this->thumblink . '" alt="' . $this->tagline . '" ';
      echo ' width="' . $w . '" height="' . $h . '" ';
      if ($class) {
         echo ' class="' . $class . '"';
      }
      if ($style) {
         echo ' class="' . $style . '"';
      }
      echo ' />';
   }

   function link_html()
   {
      echo '<a href="' . $this->filelink . '">';
      echo '<img src="' . $this->thumblink . '" alt="' . $this->tagline . '" width="' . $this->tw . '" height="' . $this->th . '"/>';
      echo '</a>';
   }

   function sq_html($link = "")
   {
      $thumbsize = vjinfo('thumb_max');
      if (!$thumbsize)
         $thumbsize = 100;
      if ($link) {
         echo '<div><a href="' . $link . '" class="sq"';
      } else {
         echo '<div class="sq"';
      }
      if ($this->tw > $this->th) {
         $top = (int) (($this->tw - $this->th) / 2) + 10;
         echo ' style="width: ' . $thumbsize . 'px; height: ' . $thumbsize . 'px; padding-top:' . $top . 'px;text-align: center;"';
      } else {
         echo ' style="width: ' . $thumbsize . 'px; height: ' . $thumbsize . 'px; text-align: center;"';
      }
      echo '>';
      echo '<img src="' . $this->thumblink . '" alt="' . $this->tagline . '" width="' . $this->tw . '" height="' . $this->th . '"/>';
      if ($link)
         echo '</a>';
      echo '</div>';
   }

   function post_thumb_html($more = "")
   {
      $thumbsize = vjinfo('thumb_max');
      if (!$thumbsize)
         $thumbsize = 100;
      echo '<div class="image">';
      if ($more)
         echo $more;
      echo '<a class="lbOn" rel="lightbox[]"';
      if ($this->tw > $this->th) {
         $top = (int) (($this->tw - $this->th) / 2) + 10;
         echo ' style="width: ' . $thumbsize . 'px; height: ' . $thumbsize . 'px; padding-top:' . $top . 'px;text-align: center;"';
      } else {
         echo ' style="width: ' . $thumbsize . 'px; height: ' . $thumbsize . 'x; text-align: center;"';
      }
      echo ' href="' . $this->filelink . '" title="' . $this->tagline . '" rel="lightbox[]">';
      echo '<img src="' . $this->thumblink . '" alt="' . $this->tagline . '" width="' . $this->tw . '" height="' . $this->th . '"/>';
      echo '</a>';
      echo '</div>';
   }

   function image_edit_html()
   {
      echo '<div class="image">';
      echo '<span></span>';
      echo '<input type="hidden" class="image-order" name="image-' . $this->imageid . '" value="" />';
      echo '<p><a href="upload-edit.php?id=' . $this->imageid . '" title="編輯圖說">編輯圖說</a>|';
      echo '<a href="upload-delete.php?id=' . $this->imageid . '" title="刪除">刪除</a></p>';
      echo '<a href="' . $this->filelink . '" title="' . $this->tagline . '" rel="lightbox[]">';
      echo '<img src="' . $this->thumblink . '" alt="' . $this->tagline . '" width="' . $this->tw . '" height="' . $this->th . '"/>';
      echo '</a>';
      echo '</div>';
   }

} // class ends here

function post_imagelist()
{
   global $vjdb, $post;

   $images = $post->post_images;
   if (empty($images))
      return;
   $count = count($images);
   if ($count < vjinfo('isalbum')) {
      echo '<div class="photolist">';
   } else {
      echo '<div class="photoalbum">';
   }
   foreach ($images as $image) {
      $myimage = new image();
      $myimage->get_imageinfo($image);
      $myimage->post_thumb_html();
   }
   if ($count >= vjinfo('isalbum')) {
      echo '<br clear="all" />';
   }
   echo '</div>';
}

?>