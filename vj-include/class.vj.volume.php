<?php

class volume {
   var $err_msg = "";
   var $volume_id = "";
   var $volume_date = 0;
   var $volume_alias = "";
   var $volume_aliasext = "";
   var $volume_topic = "";
   var $volume_topic_desc = "";
   var $volume_published = 0;
   var $volume_posts = array();

   function volume($volume=0){
      global $vj;
      if(!$volume){
	 if(is_logined()){ $volume = $vj->query_newst_volume();}
	 else { $volume = $vj->published_newst(); }
	 if(!$volume) { $this->no_vol(); }
      }
      if(!is_logined() && !cmp_published($volume)){
	 $this->err_msg = "對不起，目前系統中還沒有這一期期刊";
      } else {
	 if(!$this->query_volume($volume)){
	    $this->err_msg = "對不起，目前系統中還沒有這一期期刊";
	 } else {
	    $this->handle_title();
	    $this->get_cover();
	    if( $volume == $vj->published_newst()) {
	       $vj->thispage = 'newest';
	    } else {
	       $vj->thispage = 'archive';
	    }
	 }
      }
   }

   function update_vol_desc($array) {
      global $vj;
      if(!$array) return;
      foreach($array as $key=>$value){
	 $vj->cats[$key]->cat_vol_desc = $value;
      }
   }

   function update_vol_order($array) {
      global $vj;
      if(!$array) return;
      foreach($array as $key=>$value){
	 $vj->cats[$key]->cat_vol_order = $value;
      }
      $vj->sort_cats();
   }

   function query_volume($volume){
      global $vjdb;
      $query = "SELECT ID, CREATE_DATE, ALIAS, ALIAS_EXT, TOPIC, TOPIC_DESC, COPYRIGHT, CAT_DESC, CAT_ORDER, PUBLISHED FROM ".$vjdb->volumes." WHERE ID='$volume';";
      $result = $vjdb->get_row($query, ARRAY_A);
      if(!$result['ID']) {
	 return false;
      }
      $this->volume_id = $result['ID'];
      $this->volume_date = mysql2date('U', $result['CREATE_DATE']);
      $this->volume_alias = $result['ALIAS'];
      $this->volume_aliasext = $result['ALIAS_EXT'];
      $this->volume_topic = $result['TOPIC'];
      $this->volume_topic_desc = $result['TOPIC_DESC'];
      $this->copyright = $result['COPYRIGHT'];
      $this->volume_published = $result['PUBLISHED'];
      $this->update_vol_desc(unserialize($result['CAT_DESC']));
      $this->update_vol_order(unserialize($result['CAT_ORDER']));
      $query= "SELECT ID, TOPIC, AUTHOR, DISPLAY, CAT, IMPORTANCE FROM ".$vjdb->post." WHERE VOLUME = '$this->volume_id' ORDER BY CAT ASC,POST_ORDER ASC;";
      $this->volume_posts = $vjdb->get_results($query, ARRAY_A);
      return $this;
   }

   function no_vol() {
      $str = "<h2>您好，目前沒有任何發行紀錄</h2>";
      $str .= "<p>目前這份線上期刊還在草創的階段，還沒有任何正式的線上發行。我們非常樂意知道您對我們的刊物有興趣，歡迎您稍後再來瀏覽我們的網站。您也可以先訂閱電子郵件電子報，在出刊的時候，我們便會透過電子郵件，將期刊內容寄給您。</p>";
      if(is_logined()) {
	 $str .= '<p style="font-size: 9pt;"><strong>管理功能</strong>：因為您是管理者，您可以<a href="'.vjinfo('url')."vj-admin/volume-add.php\">新增一期期刊</a>。<p>";
      }
      vj_die($str);
   }

   function get_cover() {
      $volume = $this->volume_id;
      global $vjdb;
      $query = "SELECT * FROM ".$vjdb->images." WHERE VOLUMEID='$volume' ORDER BY ID DESC LIMIT 1;";
      $result = $vjdb->get_row($query, ARRAY_A);
      if($result) {
	 $image = new image($result);
	 $this->coverimage = $image;
      }
   }

   function cover($percent=0, $pixel = 0){
      if($this->coverimage) {
	 $this->coverimage->image_html($percent, $pixel);
      }
   }

   function handle_title() {
      $aliasstr = "第 ".$this->volume_alias." 期".$this->volume_aliasext;
      set_volalias($aliasstr);
      $datestr = date("Y 年 n 月 j 日", $this->volume_date);
      set_voldate($datestr);
      append_title($aliasstr." / ".$datestr);
   }

   function error(){
      if($this->err_msg) {
	 vj_die("<h2>".$this->err_msg."</h2>", "讀取期刊目錄時發生錯誤！");
      }
   }

   function is_published(){
      return $this->volume_published;
   }

   function post_link($id) {
      global $config;
      return $config['post_viewlink'].$id;
   }

   function style_by_importance($importance) {
      $class = array("normal", "important", "high-important", "very-important", "extra-important");
      return $class[$importance];
   }

   function get_cat_list($cat_id =0, $importance = 1, $excs=0) {
      if($excs){
	 $posts = cmp_posts($this->volume_posts, $excs);
      } else {
	 $posts = $this->volume_posts;
      }

      if(!$posts) {
	 return;
      }
      foreach($posts as $post){
	 $append = "";
	 if($post['CAT'] == $cat_id) {
	    if(empty($post['DISPLAY'])){
	       if(!is_logined()){
		  continue;
	       } else {
		  $append = "[隱藏]";
	       }
	    }
	    $str .= "<li>";
	    if($importance) {
	       $str .= '<a class="'.$this->style_by_importance($post['IMPORTANCE']).'" href="'.$this->post_link($post['ID']).'">';
	    } else {
	       $str .= '<a href="'.$this->post_link($post['ID']).'">';
	    }
	    $str .= $post['TOPIC'].$append.'</a>';
	    if($post['AUTHOR']){
	       $author = str_replace(',', '、', $post['AUTHOR']);
	       $str .= " / ".$author;
	    }
	    $str .= "</li>\n";
	 }
      }
      if($str) {
	 $str = '<ul class="cat-list" id="cat-list-'.$cat.'">'.$str.'</ul>';
	 return $str;
      }
   }

   function cat_list($cat_id) {
      echo $this->get_cat_list($cat_id);
   }

   function pub_link() {
      global $_GET;
      if($_GET['ajax']) return;
      if(!$this->volume_published){
	 echo "<div style=\"background: #900; color: #FFF; padding: 10px;\">";
	 echo "<h2 style=\"color: #FFF;\">注意：本期期刊尚未上線！</h2>";
	 echo '<form method="post" style="margin: 0;" action="'.vjinfo('url').'vj-admin/volume-publish.php">';
	 echo '<p style="font-size: 9pt; text-align: right; margin: 0; padding: 0;">這一期期刊還沒有上線，當您按下&nbsp;';
	 echo '<input type="hidden" name="topublish" value="1" />';
	 echo '<input type="hidden" name="id" value="'.$this->volume_id.'" />';
	 $refer = $_SERVER['REQUEST_URI'];
	 echo '<input type="hidden" name="refer" value="'.$refer.'" />';
	 echo '<input type="hidden" name="action" value="publish" />';
	 echo '<input type="submit" name="submit" value="上線" />';
	 echo '&nbsp;按鈕後，才會正式在網路上出版。</p></form></div>';
      } 
   }
}

function volume_alias($before="", $after=""){
   global $volume;
   if($volume->volume_alias) {
      echo $before;
      echo $volume->volume_alias;
      echo $after;
   }
}

function volume_aliasext($before="", $after=""){
   global $volume;
   if($volume->volume_aliasext) {
      echo $before;
      echo $volume->volume_aliasext;
      echo $after;
   }
}

function volume_topic($before="", $after="", $break = 0){
   global $volume;
   if($volume->volume_topic){
      $topic = $volume->volume_topic;
      if($break) {
	 $topic = str_replace("－", "－<br/>", $topic);
      }
      echo $before;
      echo $topic;
      echo $after;
   }
}

function volume_topic_desc($before="", $after=""){
   global $volume;
   if($volume->volume_topic_desc) {
      echo $before;
      echo $volume->volume_topic_desc;
      echo $after;
   }
}

function volume_copyright($before="", $after=""){
   global $volume;
   if($volume->copyright) {
      echo $before;
      echo $volume->copyright;
      echo $after;
   }
}

function cat_list($cat) {
   global $volume;
   return $volume->cat_list($cat);
}

function pub_link(){
   global $volume;
   return $volume->pub_link();
}

function cover($percent=0, $pixel = 0){
   global $volume;
   return $volume->cover($percent, $pixel);
}

function cmp_posts($posts, $excs) {
   $newposts = array();
   foreach($posts as $post){
      $n = 0;
      foreach($excs as $exc) {
	 if($post['ID'] == $exc)  { $n = 1; }
      }
      if(!$n) {
	 $newposts[] = $post;
      }
   }
   return $newposts;
}


function cmp_cats($cats, $excs=0) {
   $newcats = array();
   foreach($cats as $cat){
      $n = 0;
      foreach($excs as $exc) {
	 if($cat->cat_id == $exc)  { $n = 1; }
      }
      if(!$n) {
	 $newcats[] = $cat;
      }
   }
   return $newcats;
}

   /* 參數列表 
   exc: 排除的分類 id(不要顯示)
   useimage: 是否使用分類圖片
   imagestyle: 圖片的 css style
   usecatdesc: 使用該期該分類詳細說明
    */
function volume_list_by_cat($exc=0,$useimage =0, $imagestyle="",$usecatdesc=0, $usevolcatdesc=0){
   global $vj;
   global $volume;
   if($vj->cats) {
      $cats = cmp_cats($vj->cats, $exc);
      foreach($cats as $cat) {
	 $str = $volume->get_cat_list($cat->cat_id);
	 if($str){
	    echo '<h3 class="cat-title" id="cat-title-'.$cat->cat_id.'">'.$cat->cat_name;
	    if($cat->cat_desc && $usecatdesc) { echo " - ".$cat->cat_desc; }
	    echo "</h3>";
	    if($useimage) list_by_cat_img($cat->cat_id, $imagestyle);
	    if($usevolcatdesc) {
	       if($cat->cat_vol_desc) {
		  echo $cat->cat_vol_desc;
	       }
	    }
	    echo $str;
	 }
      }
   }
}

function list_by_cat_img($cat=0, $style="") {
   global $vjdb, $volume;
   if(!$cat) return;
   $posts = $volume->volume_posts;
   foreach($posts as $post){
      $mypost = new post(0, 0);
      $mypost->get_postinfo($post);
      $mypost->handle_link();
      if($mypost->post_cat == $cat && $mypost->display) {
	 $query = "SELECT * FROM ".$vjdb->images." WHERE POSTID='".$mypost->post_id."' AND DISPLAY='1' ORDER BY IMAGE_ORDER ASC, ID DESC LIMIT 1;";
	 $image = $vjdb->get_row($query, ARRAY_A);
	 if(!$image) continue;
	 echo '<div style="'.$style.'">';
	 $myimage = new image($image);
	 $myimage->sq_html($mypost->post_permalink);
	 echo '</div>';
	 return;
      }
   }
}

?>
