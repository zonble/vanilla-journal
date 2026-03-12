<?php volume_list_by_cat(array(), 1, "float: right; padding-bottom: 10px;", 0, 1); ?>
<div id="footer">
<?php echo vjinfo('credit'); ?>
</div>
</div>

<div id="sidebar">
<?php if($volume) { ?>
<div class="featurebox">
<h3>本期主題： <?php volume_topic();?></h3>
<?php cover(0, 150) ?>
<?php volume_topic_desc('<div class="volume-topic-desc">', '</div>');?>
</div>
<?php } ?>
