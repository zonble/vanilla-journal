<?php

   if(!file_exists("../config.php")) {
      header('Content-type: text/html; charset=UTF-8', true);
      echo "<h1>執行錯誤！您還沒有建立您的 Vanilla Jounal 設定檔l！</h1>";
      echo "<p>您的系統目錄中還沒有 config.php 檔案，您必須在這個檔案中寫入基本設定，才可以
	 使用 Vanilla Journal 期刊系統。</p>";
      die();
   }

   require_once("../config.php");
   require_once("../vj-include/vj.php");
   require_once("./admin-function.php");
   require_once("./admin-forms.php");

   /* 如果資料庫還沒有裝起來，跳回安裝畫面 */

   if(!vjinfo('url')) {
         header("Location: ../.");
   }

   /* 如果密碼不對，不可以使用管理介面頁面 */
   if(!$loginpage) {
      if(!is_logined()) {
         header("Location: login.php");
      }
   }

   if($_POST['ajax']) {
      $ajax = $_POST['ajax'];
   } else {
      $ajax = $_GET['ajax'];
   }
?>
