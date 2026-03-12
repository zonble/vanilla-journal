<?php

   if(!file_exists("config.php")) {
      header('Content-type: text/html; charset=UTF-8', true);
      echo "<h1>執行錯誤！您還沒有建立您的 Vanilla Jounal 設定檔！</h1>";
      echo "<p>您的系統目錄中還沒有 config.php 檔案，您必須在這個檔案中寫入基本設定，才可以使用 Vanilla Journal 期刊系統。</p>";
      die();
   }

   require("config.php");
   require("vj-include/vj.php");
   $is_email = $_GET['is_email'];
   $ajax = $_GET['ajax'];

   if(!vjinfo('url')) {
      header('Content-type: text/html; charset=UTF-8', true);
      echo "<h1>執行錯誤！您還沒有安裝 Vanilla Journal！</h1>";
      echo "<p>因為您還沒有安裝 Vanilla Journal「香草期刊系統」，所以您現在還無法正確使用。</p>";
      $vjdb->reset_error();
      $db_name = $config['db_name'];
      $vjdb->query("USE $db_name");
      if($vjdb->last_error) {
	 echo "<p>目前 MySQL 系統中還沒有您想要使用的資料庫，請手動建立您要使用的資料庫「".$db_name."」，您可以使用 myql 指令或是用 phpmyadmin 等工具建立。關於詳細的操作，您可以參看使用說明。</p>";
      } else {
	 echo "<ul><li>如果您要開始安裝 Vanilla Journal，請點選「<a href=\"install/install.php\">安裝程式</a>」。</li><li>如果您不清楚如何安裝 Vanilla Journal，請點選 Vanilla Journal 專案網頁上的<a href=\"http://code.google.com/p/vanilla-journal/wiki/InstallVanillaJournal\">安裝說明</a>，了解如何安裝。</li></ul>";
	 echo "<hr />";
	 echo "<p><a href=\"http://code.google.com/p/vanilla-journal/\">Vanilla Journal</a></p>";
      }
      die();
   }
?>