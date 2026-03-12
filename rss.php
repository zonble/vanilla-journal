<?php include("vj-header.php") ?>
<?php

/* 如果沒有指定，就是最新的一期囉 */
if (empty($this_volume)) {
   $this_volume = published_newst();
} 
header('Content-type: text/xml; charset=UTF-8', true);
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:feedburner="http://rssnamespace.org/feedburner/ext/1.0" version="2.0">
<channel>
<title><?php echo vjinfo('title'); ?></title>
<link><?php echo vjinfo('url'); ?></link>
<description><?php echo vjinfo('description'); ?></description>
<language>en</language>
<?php
if ($this_volume) {
   $query = "SELECT ID, CREATE_DATE, ALIAS, TOPIC, PUBLISHED FROM $vjdb->volumes WHERE ID='$this_volume';";
   $info = $vjdb->get_row($query, ARRAY_A);
   $date = mysql2date('D, d M Y H:i:s +0000', $results[0]['CREATE_DATE']);
   $query = "SELECT ID, TOPIC, CAT, BODY, AUTHOR FROM $vjdb->post WHERE VOLUME='$this_volume' ORDER BY CAT ASC, POST_ORDER ASC";
   $posts = $vjdb->get_results($query, ARRAY_A);
   if($posts) {
      foreach($posts as $post) {
   ?>
   <item>
     <title><?php echo $post['TOPIC']; ?></title>
     <link><?php echo vjinfo('url')."viewpost.php?id=".$post['ID']; ?></link>
     <pubDate><?php echo $date; ?></pubDate>
     <?php if($post['AUTHOR']) { ?>
     <dc:creator><?php echo $post['AUTHOR']; ?></dc:creator>
     <?php } ?>
     <category><?php echo cat_name($post['CAT']); ?></category>
     <content:encoded><![CDATA[ <?php echo $post['BODY']; ?> ]]></content:encoded>
   </item>
   <?
      }
   }
}
      ?>
</channel>
</rss>
