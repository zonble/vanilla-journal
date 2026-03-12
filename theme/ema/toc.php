<?php
   if($volume->coverimage->w) {
      $myw = $volume->coverimage->w;
      $myw = (int)($myw * 0.8);
   } else {
      $myw = 250;
   }
   $excepts = array();
   $excepts[0] = "2";
   if($myw) {
      $myw2 = 677 - $myw;
      echo "<style type=\"text/css\">";
      echo '@media screen {';
      echo '.featured { width:'.$myw.'px;float: left; padding: 10px; margin-right: 10px;}';
      echo '.toc {width:'.$myw2.'px;float: right; }';
      echo '#paper {width: 720px; }';
      echo "}</style>";
   }
?>
<div id="paper">
<fieldset class="featured">
<legend class="volume-topic-tag">本期主題</legend>
<?php volume_topic('<h2 class="volume-topic">', '</h2>', 1);?>
<?php cover(80) ?>
<br />
<?php volume_topic_desc('<div class="volume-topic-desc">', '</div>');?>
<?php cat_list(2) ?>
</fieldset>
<div class="toc">
<?php volume_list_by_cat($excepts, 1, "float: right; padding-bottom: 10px;", 0, 1); ?>
</div> 
<br clear="all" />
</div>
