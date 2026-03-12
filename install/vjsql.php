<?php

function myquery($query) {
   global $vjdb;
   $vjdb->query($query);
}

function install_db($title="", $url="", $password="") {
global $vjdb;

$query ="CREATE TABLE `$vjdb->infos` (
 `ID` int(13) NOT NULL auto_increment,
 `KEY` VARCHAR(50) NOT NULL,
 `VALUE` VARCHAR(1000) NOT NULL,
 PRIMARY KEY (`ID`)
 ) ";
myquery($query);

$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('title', '$title');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('description', '');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('url', '$url');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('publisher', 'Vanilla Journal');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('sender', 'example@example.com');";
myquery($query);	
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('sender_name', '[Vanilla Journal]');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('isalbum', '5');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('thumb_max', '100');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('image_max', '350');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('image_path', 'vj-upload');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('attach_path', 'vj-attachment');";
myquery($query);
$mypassword = md5($password);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('password', '$mypassword');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('theme', 'default');";
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('credit', '<p>Vanilla Journal</p>');";
myquery($query);
$query = "INSERT INTO `$vjdb->infos` (`KEY`, `VALUE`) VALUES ('about', '<h2>關於我們</h2>');";
myquery($query);

$query = "CREATE TABLE `$vjdb->volumes` (
 `ID` int(13) NOT NULL auto_increment,
 `ALIAS` VARCHAR( 20 ) NOT NULL ,
 `ALIAS_EXT` VARCHAR( 100 ) NOT NULL ,
 `TOPIC` VARCHAR(100) NOT NULL ,
 `TOPIC_DESC` TEXT NOT NULL ,
 `COPYRIGHT` TEXT NOT NULL ,
 `CAT_DESC` TEXT NOT NULL ,
 `CAT_ORDER` TEXT NOT NULL ,
 `PUBLISHED` BOOL NOT NULL,
 `CREATE_DATE` datetime NOT NULL default '0000-00-00 00:00:00',
 PRIMARY KEY (`ID`)
)";
myquery($query);

$query = "CREATE TABLE `$vjdb->cat` (
 `ID` int(13) NOT NULL auto_increment,
 `CAT_NAME` VARCHAR( 50 ) NOT NULL ,
 `CAT_DESC` VARCHAR( 200 ) NOT NULL ,
 PRIMARY KEY (`ID`)
)";
myquery($query);

$query = "INSERT INTO `$vjdb->cat` (`CAT_NAME`, `CAT_DESC`) VALUES ('編者的話', '');";
myquery($query);
$query = "INSERT INTO `$vjdb->cat` (`CAT_NAME`, `CAT_DESC`) VALUES ('專題', '');";
myquery($query);
$query = "INSERT INTO `$vjdb->cat` (`CAT_NAME`, `CAT_DESC`) VALUES ('書評', '');";
myquery($query);
$query = "INSERT INTO `$vjdb->cat` (`CAT_NAME`, `CAT_DESC`) VALUES ('論壇', '');";
myquery($query);

$query = "CREATE TABLE `$vjdb->post` (
  `ID` int(13) NOT NULL AUTO_INCREMENT,
  `VOLUME` int(13) NOT NULL DEFAULT '0',
  `CAT` int(13) NOT NULL DEFAULT '0',
  `TOPIC` varchar(200) NOT NULL DEFAULT '',
  `TOPIC_EXT` varchar(200) NOT NULL DEFAULT '',
  `AUTHOR` varchar(200) NOT NULL DEFAULT '',
  `AUTHOR_DESC` text NOT NULL,
  `BODY` text NOT NULL,
  `ABSTRACT` text NOT NULL,
  `KEYWORD` varchar(200) NOT NULL DEFAULT '',
  `DISPLAY` tinyint(1) DEFAULT NULL,
  `POST_ORDER` int(4) NOT NULL DEFAULT '0',
  `POST_DATE` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `IMPORTANCE` int(4) NOT NULL DEFAULT '0',
  `COUNT` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)  
) ";
myquery($query);

$query = "CREATE TABLE `$vjdb->images` (
 `ID` BIGINT(20) NOT NULL auto_increment,
 `TAGLINE` VARCHAR(500) NOT NULL,
 `FILENAME` VARCHAR(100) NOT NULL,
 `FILEPATH` VARCHAR(100) NOT NULL,
 `THUMB` VARCHAR(100) NOT NULL,
 `SIZE` VARCHAR(200) NOT NULL,
 `DISPLAY` BOOL,
 `UPLOAD_DATE` datetime NOT NULL default '0000-00-00 00:00:00',
 `VOLUMEID` BIGINT(20),
 `POSTID` BIGINT(20),
 `IMAGE_ORDER` INT(4) NOT NULL,
 PRIMARY KEY  (`ID`)
) ";
myquery($query);

$query = "CREATE TABLE `$vjdb->subscribers` (
 `ID` BIGINT(20) NOT NULL auto_increment,
 `EMAIL` VARCHAR(100) NOT NULL,
 `VERIFIED` BOOL,
 `NAME` VARCHAR(100) NOT NULL,
 `HASH` VARCHAR(100) NOT NULL,
 `CREATE_DATE` datetime NOT NULL default '0000-00-00 00:00:00',
 `VERIFIED_DATE` datetime NOT NULL default '0000-00-00 00:00:00',
 PRIMARY KEY (`ID`)
)" ;
myquery($query);

$query = "INSERT INTO $vjdb->subscribers (EMAIL, VERIFIED, HASH, NAME) VALUES ('user@example.com', '1', '6c465af55bd95e740446a095f344df3f', 'Example User');";
myquery($query);

$query = "CREATE TABLE `$vjdb->attaches` (
 `ID` BIGINT(20) NOT NULL auto_increment,
 `TAGLINE` VARCHAR(500) NOT NULL,
 `FILENAME` VARCHAR(100) NOT NULL,
 `FILEPATH` VARCHAR(100) NOT NULL,
 `FILESIZE` INT(13) NOT NULL,
 `FILETYPE` VARCHAR(40) NOT NULL,
 `UPLOAD_DATE` datetime NOT NULL default '0000-00-00 00:00:00',
 `DISPLAY` BOOL,
 `POSTID` BIGINT(20),
 PRIMARY KEY (`ID`)
); ";
myquery($query);

$query = "CREATE TABLE `$vjdb->feeds` (
 `ID` BIGINT(20) NOT NULL auto_increment,
 `URL` VARCHAR(300) NOT NULL,
 `TITLE` VARCHAR(200) NOT NULL,
 PRIMARY KEY (`ID`)
); ";
myquery($query);
}
?>