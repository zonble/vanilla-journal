<?php 
   $id = $_GET['id'];
   $post = new post($id);
   $post->error();

   vj_header();
   vj_post();
   if(is_logined() && !$ajax) {
      $editlink = $config['post_editlink'].$id;
      echo '<p style="font-size: 9pt;text-align: center;"><strong>管理功能</strong>：因為您是管理者，您可以<a href="'.$editlink."\">修改這篇文章</a>。<p>";
   }
   vj_footer();
?>
