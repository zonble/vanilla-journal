<?php 
function showactive($page) {
   global $vj;
   if($vj->thispage == $page) {
      echo "class=\"active\" ";
   }
}
?>
<ul id="nav">
    <li <?php showactive('newest') ?>><a href="<?php info('url');?>index.php" title="最新期電子報">最新電子報</a></li>
    <li <?php showactive('archive') ?>><a href="<?php info('url');?>volume.php"
            title="前期電子報索引">期數索引</a><?php show_archive_nav(); ?></li>
    <li <?php showactive('subscribe') ?>><a href="<?php info('url');?>signup.php" title="電子報訂閱、退訂">訂閱、退訂</a></li>
    <li <?php showactive('search') ?>><a href="<?php info('url');?>search.php" title="電子報內容搜尋">內容搜尋</a></li>
    <li <?php showactive('about.php') ?>><a href="<?php info('url');?>index.php?file=about.php" title="關於我們">關於我們</a>
    </li>
    <li><a href="<?php info('url');?>rss.php" title="RSS">RSS</a></li>
    <li><a href="<?php info('url');?>vj-admin/" title="發報管理介面">管理介面</a></li>
</ul>