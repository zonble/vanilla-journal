<?php

/* Vanilla Journal
 * vj.php
 * Weizhong Yang
 */
require_once("vj.db.php");
require_once("class.vj.php");
require_once("class.vj.post.php");
require_once("class.vj.html.php");
require_once("class.vj.volume.php");
require_once("class.vj.image.php");
require_once("class.vj.attach.php");
require_once("class.phpmailer.php");
require_once("class.upload.php");
require_once("vj.function.php");
$vj = new vj();
$html = new html();
require_once("vj.setting.php");
$vj->set_links();
?>
