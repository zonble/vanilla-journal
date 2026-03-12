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

Vanilla Journal 旨在讓使用者能夠輕鬆管理電子報的期數、訂閱者以及發佈流程。

在那個 WordPress 還未統治全網、Composer 與 npm 尚未發明的年代，這是一個輕巧且自給自足的解決方案。所有的功能與依賴都包含在專案源碼中，不需要執行任何安裝指令即可部署。

在 2007 年前後，這套系統曾被約十來所大專院校或系所採用作為電子報發佈方案。之所以在當時受到青睞，主因是那年代的主流 Blog 系統（如無名小站、早期 WordPress）或學術圈盛行的 BBS 系統，普遍缺乏「主動寄送電子郵件給訂閱者」的功能。Vanilla Journal 填補了這個需求，讓單位能主動將精選內容推送到讀者的信箱中。

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
- **套件管理**：**沒有 Composer，也沒有 npm/yarn！** 在那個年代，還沒有現代化的套件管理概念。開發者唯一的「安裝」方式就是手動前往官網下載 Zip 檔、解壓縮，然後把原始碼直接拷貝（Copy & Paste）進專案目錄中。這也是為什麼你會在 `vj-include/` 中看到一堆第三方 PHP 類別庫，在 `vj-script/` 中看到整套 JS 框架的原因。
- **品質保證**：**完全沒有單元測試 (Unit Tests)！** 在那個 PHPUnit 尚未普及、TDD (Test-Driven Development) 還不是主流開發模式的年代，主要的測試方式就是「重新整理網頁」並觀察結果。程式碼的品質全靠開發者的細心以及「人體點擊測試」來維持。

## 系統架構導覽 (Architecture Mapping)

如果你習慣了現代的 MVC 框架或單一入口（Single Entry Point）架構，這裡是一份給現代開發者的導覽圖，幫助你理解當時的架構思維：

- **路由 (Routing)**：沒有前端路由或 `index.php` 統一轉發。使用者點擊什麼功能，就直接瀏覽對應的 PHP 檔案（例如：訪問首頁就是 `index.php`，閱讀文章是 `read.php`，註冊是 `signup.php`）。
- **設定檔 (Configuration)**：手動將 `config.php.sample` 複製為 `config.php` 並填入資料庫帳密。當時還沒有 `.env` 環境變數檔案的概念。
- **後台管理 (Admin)**：所有管理者相關的功能都被實體隔離在 `vj-admin/` 資料夾下。
- **資料庫安裝 (Installation)**：沒有 Migration 腳本，安裝是透過瀏覽器執行 `install/` 目錄下的腳本（`vjsql.php`），直接將預設的結構匯入資料庫中。

## 安裝與執行 (Installation)

在 2000 年代，部署 PHP 專案的方式通常非常直覺（也相對原始）。若要重現這個系統，請遵循以下步驟：

1. **部署檔案**：將整個專案目錄（包含所有子目錄與檔案）搬移至 Web 伺服器的根目錄（如 `www/` 或 `public_html/`）或其子目錄下。
2. **準備設定檔**：將 `config.php.sample` 重新命名或複製為 `config.php`，並編輯其中的資料庫連線資訊（DB_NAME, DB_USER, DB_PASSWORD 等）。
3. **執行安裝腳本**：在瀏覽器中造訪專案路徑下的 `/install/index.php`。該腳本會根據 `vjsql.php` 的內容建立資料庫資料表與初始資料。
4. **手動安全性清理**：**安裝完成後，你必須手動刪除整個 `install/` 目錄**。當時還沒有自動化部署工具，這類安全性清理工作全靠開發者的記憶力。
5. **權限設定**：確保 `vj-tmp/`、`vj-upload/` 與 `vj-attachment/` 目錄對於 Web 伺服器進程具備寫入權限。

### 現代環境模擬 (Docker)

由於現代 PHP (PHP 7.0+) 已經徹底移除了舊版的 `mysql_*` 函數，你無法在目前的環境中直接運行。為了方便考古，本專案已提供 `Dockerfile` 與 `docker-compose.yml` 來一鍵建立古老的執行環境（PHP 5.6 + MySQL 5.5）：

1. 請確保你的電腦已安裝 Docker Desktop 或 Docker Engine。
2. 複製設定檔：將 `config.php.sample` 複製為 `config.php`，並將資料庫的設定改為 `docker-compose.yml` 中配置的值：
   - `DB_HOST`: `db`
   - `DB_NAME`: `vanilla_journal`
   - `DB_USER`: `vj_user`
   - `DB_PASSWORD`: `vj_password`
3. 在終端機執行 `docker-compose up -d` 啟動伺服器。
4. 打開瀏覽器訪問：[http://localhost:8080/install/index.php](http://localhost:8080/install/index.php) 執行資料庫安裝。
5. （非強制的懷舊體驗）安裝完成後，體驗手動將 `install/` 目錄刪除的樂趣。

---

_「程式碼會隨著時間老去，但那些解決問題的巧思與熱情，將永遠留在這些字裡行間。」_
