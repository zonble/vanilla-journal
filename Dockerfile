FROM php:5.6-apache

# 安裝舊版 PHP 的 mysql 擴充模組 (Vanilla Journal 依賴舊版的 mysql_connect)
RUN docker-php-ext-install mysql mysqli pdo_mysql

# 啟用 Apache rewrite 模組
RUN a2enmod rewrite

# 將目前目錄下的程式碼複製到容器內的網站根目錄
COPY . /var/www/html/

# 設定需要寫入權限的資料夾
RUN mkdir -p /var/www/html/vj-tmp \
    && mkdir -p /var/www/html/vj-upload \
    && mkdir -p /var/www/html/vj-attachment \
    && chown -R www-data:www-data /var/www/html/vj-tmp /var/www/html/vj-upload /var/www/html/vj-attachment \
    && chmod -R 777 /var/www/html/vj-tmp \
    && chmod -R 777 /var/www/html/vj-upload \
    && chmod -R 777 /var/www/html/vj-attachment
