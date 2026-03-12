<?php// print_r($post); ?>
<div class="left">
<?php // post_backlink(); ?>
<p class="tag"><?php post_cat(); ?></p>

<h2 class="topic"><?php post_topic(); ?></h2>
<p class="byline"><?php post_author(); ?></p>
<?php post_author_desc('<div class="byline">作者簡介：','</div>'); ?>
<?php post_imagelist(); ?>
<?php post_keyword('<p>本文關鍵字：','</p>'); ?>
<?php post_abstract('<fieldset class="post-body"><legend>本文摘要</legend>', '</fieldset>'); ?>
<?php post_body('<div class="post-body">', '</div><br clear="all" />'); ?>
<?php post_attachlist("<fieldset><legend>本文附件：</legend>","</fieldset>"); ?>
</div>

<?php post_backlink(); ?>
<p>» <a href="#" id="cite-link">在學術著作上引用這篇文章</a></p>
<fieldset id="cites">
<legend>學術引用格式</legend>
<p>如果您想要在學術著作上引用這篇文章，可以使用以下書目格式：</p>
<ul>
<li><strong><abbr title="American Psychological Association">APA</abbr> 格式</strong>：<br /><small><?php post_cite_apa(); ?></small></li>
<li><strong><abbr title="Modern Language Association">MLA</abbr> 格式</strong>：<br /><small><?php post_cite_mla(); ?></small></li>
<li><strong>EndNote 匯入格式</strong>：<a href="<?php post_endnotelink(); ?>" title="下載 EndNote 格式匯入檔案">下載</a><br />
   <small>下載後請在 EndNote 軟體中選擇「File」→ 「Import」功能匯入，並且在開啟檔案對話視窗中，將文字編碼設定為 Unicode（UTF-8），便可以在 EndNote 中使用這篇文章的資料。</small>
   </li>
</ul>
</fieldset>
<?php realtive_link("<h3>本期同分類其他文章</h3>") ?>
