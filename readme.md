## 關於SchoolWeb
### 安裝php lib
sudo apt-get install php7.0-ldap
sudo apt-get install sendmail


### 安裝
git clone https://github.com/wangchifu/SchoolWeb.git

進入 SchoolWeb
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan config:clear

編輯 .env