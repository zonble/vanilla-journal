# Vanilla Journal (香草電子報)

> 🏛 **Web 2.0 歷史遺跡 (Circa 2007)**
>
> 這是我在 2007 年於學校工作時，為了系所的電子報所開發的輕量級管理系統。本專案以開源方式釋出，隨著離職後已停止維護。它完美保留了 2000 年代中期（Web 1.0 晚期至 Web 2.0 早期）的 PHP 網頁開發面貌與技術堆疊。

⚠️ **考古警告：請勿在現代伺服器上執行**
本專案僅供歷史存檔與研究用途。程式碼包含該年代常見的寫法，且依賴已廢棄的 PHP 函數（例如 `mysql_connect`）。若將其部署在公開且現代的 Web 伺服器上，將面臨極大的安全風險（如 SQL Injection 或 XSS 漏洞）。

## 原始出處

本專案原本託管於 Google Code（該服務亦已走入歷史）：
[https://code.google.com/archive/p/vanilla-journal](https://code.google.com/archive/p/vanilla-journal)

## 專案簡介

Vanilla Journal 旨在讓使用者能夠輕鬆管理電子報的期數、訂閱者以及發佈流程。在那個 WordPress 還未統治全網、Composer 尚未發明的年代，這是一個輕巧且自給自足的解決方案。

### 核心功能

- **期數管理 (Volumes)**：支援按期數組織內容，並提供自動產生的目錄 (TOC)。
- **訂閱系統**：包含訂閱者註冊 (`signup.php`)、匯入與搜尋功能。
- **管理後台 (`vj-admin/`)**：提供視覺化的管理介面，支援文章編輯、附件管理與郵件發送。
- **佈景主題 (Themes)**：支援多種佈景（如 `default`, `ema`, `unews`），具備彈性的外觀自定義能力。
- **RSS 支援**：提供 RSS feed 輸出，方便讀者訂閱（在當時這是非常時髦且必備的功能！）。
- **附件與上傳**：內建檔案上傳與管理功能。

## 技術時空膠囊 (2007 年代技術棧)

這份原始碼是一個絕佳的教材，展示了現代框架（如 Laravel 或 Vue.js）普及之前的開發方式：

- **後端語言**：PHP 4.x / 5.x（混合了程序導向與早期物件導向的寫法）。
- **資料庫**：MySQL 4.x / 5.x。使用了當時非常流行的 `ezSQL` 類別庫來封裝資料庫操作。
- **前端 JavaScript**：`Prototype.js` & `script.aculo.us`。在 jQuery 稱霸與現代前端框架出現之前，這是主流的 AJAX 與特效解決方案。
- **內容編輯器**：`TinyMCE`（古董級的 WYSIWYG 視覺化編輯器）。
- **依賴管理**：**沒有 Composer！** 所有的第三方套件（如 `PHPMailer`, `Snoopy`, `ezSQL`）都是直接把原始碼下載後，複製貼上到 `vj-include/` 資料夾中。

## 系統架構導覽 (Architecture Mapping)

如果你習慣了現代的 MVC 框架或單一入口（Single Entry Point）架構，這裡是一份給現代開發者的導覽圖，幫助你理解當時的架構思維：

- **路由 (Routing)**：沒有前端路由或 `index.php` 統一轉發。使用者點擊什麼功能，就直接瀏覽對應的 PHP 檔案（例如：訪問首頁就是 `index.php`，閱讀文章是 `read.php`，註冊是 `signup.php`）。
- **設定檔 (Configuration)**：手動將 `config.php.sample` 複製為 `config.php` 並填入資料庫帳密。當時還沒有 `.env` 環境變數檔案的概念。
- **後台管理 (Admin)**：所有管理者相關的功能都被實體隔離在 `vj-admin/` 資料夾下。
- **資料庫安裝 (Installation)**：沒有 Migration 腳本，安裝是透過瀏覽器執行 `install/` 目錄下的腳本（`vjsql.php`），直接將預設的結構匯入資料庫中。

## 如何重現這個歷史遺跡？ (Emulation)

由於現代 PHP (PHP 7.0+) 已經徹底移除了舊版的 `mysql_*` 函數，你無法在現在的電腦上直接運行它。如果你真的想一窺它當年的風貌，建議使用 Docker 來建立一個古老的執行環境：

1. 使用 `php:5.6-apache`（或更舊的版本）作為 Web 伺服器容器。
2. 使用 `mysql:5.5` 或更舊的版本作為資料庫容器。
3. 確保 `vj-tmp/`, `vj-upload/`, `vj-attachment/` 具備 Web Server 的寫入權限。
4. 設定好 `config.php` 後，在瀏覽器中訪問 `/install/` 完成資料庫初始化。

---

_「程式碼會隨著時間老去，但那些解決問題的巧思與熱情，將永遠留在這些字裡行間。」_
