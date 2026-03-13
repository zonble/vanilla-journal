<?php

/* Vanilla Journal
 * vj.db.php
 * Weizhong Yang
 */

require_once("class.ezsql.core.php");
require_once("class.ezsql.mysql.php");
$vjdb = new ezSQL_mysql($config['db_user'], $config['db_password'], $config['db_name'], $config['db_host']);
$vjdb->infos = $config['db_prefix'] . "info";
$vjdb->volumes = $config['db_prefix'] . "volumes";
$vjdb->cat = $config['db_prefix'] . "cat";
$vjdb->post = $config['db_prefix'] . "post";
$vjdb->images = $config['db_prefix'] . "images";
$vjdb->subscribers = $config['db_prefix'] . "subscribers";
$vjdb->attaches = $config['db_prefix'] . "attaches";
$vjdb->feeds = $config['db_prefix'] . "feeds";
?>