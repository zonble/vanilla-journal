<?php
   include("vj-header.php");
   $posts = array();
   foreach($_POST as $post) {
      $posts[] = $post;
   }

 //  $post->error();
   header('Content-type: text/txt;charset=utf-8',true);
   header('Expires: ' . $now);
   if (PMA_USR_BROWSER_AGENT == 'IE') {
      header('Content-Disposition: inline; filename="endnote_import.txt"');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
   } else {
      header('Content-Disposition: attachment; filename="endnote_import.txt"');    
      header('Pragma: no-cache');
   }

   foreach($posts as $id){
      $post = new post($id);
?>
%0 Electronic Source
%~ <?php info('title'); echo "\n"; ?>
%I <?php info('publisher'); echo "\n"; ?>
%W <?php info('publisher'); echo "\n"; ?>
%T <?php post_topic(); echo "\n"; ?>
%! <?php post_topic(); echo "\n"; ?>
%A <?php post_author_plain(); echo "\n"; ?>
%U <?php post_permalink(); echo "\n"; ?>
%D <?php post_year(); echo "\n"; ?>
%V <?php info('now_year'); echo "\n"; ?>
%N <?php info('now_date'); echo "\n"; ?>

<?php
   }

?>
