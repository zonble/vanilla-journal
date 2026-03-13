<?php

/* Vanilla Journal
 * vj.setting.php
 * Weizhong Yang
 */

$config['version'] = "0.256";

if (vjinfo('theme')) {
   $config['theme'] = vjinfo('theme');
} else {
   $config['theme'] = "default";
}
$config['post_viewlink'] = vjinfo('url') . "index.php?id=";
$config['post_endnotelink'] = vjinfo('url') . "vj-endnote.php?id=";
$config['post_editlink'] = vjinfo('url') . "vj-admin/post-edit.php?id=";
$config['vol_viewlink'] = vjinfo('url') . "index.php?volume=";
$config['vol_editlink'] = vjinfo('url') . "vj-admin/volume-info.php?id=";
$config['vol_addlink'] = vjinfo('url') . "vj-admin/volume-add.php";
$config['authorsearchurl'] = vjinfo('url') . "search.php?author=";
$config['keywordsearchurl'] = vjinfo('url') . "search.php?keyword=";
$config['image_url'] = vjinfo('url') . vjinfo('image_path') . "/";
$config['image_path'] = $config['basepath'] . vjinfo('image_path') . "/";
$config['attach_url'] = vjinfo('url') . vjinfo('attach_path') . "/";
$config['attach_path'] = $config['basepath'] . vjinfo('attach_path') . "/";
$config['theme_url'] = vjinfo('url') . "theme/" . $config['theme'] . "/";
$config['theme_path'] = $config['basepath'] . "theme/" . $config['theme'] . "/";
?>