# Vanilla Journal

這是我在 2007 年開發的一個電子報系統。

當時我還在學校工作，原本是為了系所的電子報開發，並且以開源的方式分享，但隨著離職就沒有繼續維護了，當中使用的也都是當時所支援的技術。

## 專案狀態

本專案目前**已停止維護**，僅作為歷史存檔用途。

## 原始出處

本專案原本託管於 Google Code：
[https://code.google.com/archive/p/vanilla-journal](https://code.google.com/archive/p/vanilla-journal)

## 專案簡介

Vanilla Journal 是一個輕量級的電子報管理系統，旨在讓使用者能夠輕鬆管理電子報的期數、訂閱者以及發佈流程。

### 核心功能

- **期數管理 (Volumes)**：支援按期數組織內容，並提供自動產生的目錄 (TOC)。
- **訂閱系統**：包含訂閱者註冊 (`signup.php`)、匯入與搜尋功能。
- **管理後台 (`vj-admin/`)**：提供視覺化的管理介面，支援文章編輯、附件管理與郵件發送。
- **佈景主題 (Themes)**：支援多種佈景（如 `default`, `ema`, `unews`），具備彈性的外觀自定義能力。
- **RSS 支援**：提供 RSS feed 輸出，方便讀者訂閱。
- **附件與上傳**：內建檔案上傳與管理功能。

### 技術棧 (2007 年代)

- **後端**：PHP
- **資料庫**：MySQL (使用 `ezSQL` 類別庫進行資料庫封裝)
- **前端 JavaScript**：Prototype.js & script.aculo.us (當年主流的 AJAX 框架)
- **內容編輯器**：TinyMCE
- **郵件發送**：PHPMailer
- **其他工具**：使用 Snoopy 類別進行遠端網頁抓取與處理。

## 環境需求

由於本專案開發於 2007 年，其架構與語法依賴當時的環境配置。若要順利運行，建議符合以下條件：

- **PHP 版本**：需要 **PHP 4.x** 或 **PHP 5.x**（建議在 PHP 5.6 以下）。因為系統底層的資料庫連線（ezSQL 模組）依賴了舊版的 `mysql_connect()` 函數，而此函數已在 PHP 7.0 後被完全移除。
- **MySQL 版本**：支援 **MySQL 4.x** 或 **MySQL 5.x**。
- **目錄權限**：系統需要具備檔案寫入能力，請確保 `vj-tmp/`、`vj-upload/` 與 `vj-attachment/` 等目錄開放了 Web Server 的寫入權限。

## 快速安裝參考

1.  將 `config.php.sample` 複製並更名為 `config.php`。
2.  編輯 `config.php` 並填入正確的資料庫連線資訊。
3.  執行 `install/` 目錄下的安裝指令碼完成資料庫初始化。
