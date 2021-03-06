## 關於SchoolWeb
### 安裝php lib
#### 佈好laravel的環境
- sudo apt-get install unzip zip
- sudo apt-get install php7.0-cli  php7.0-json php7.0-mcrypt php7.0-mbstring php7.0-gd php7.0-xml
- sudo apt-get install php7.0-ldap

#### 下載composer
- wget -c https://getcomposer.org/composer.phar
- chmod +x composer.phar
- sudo mv composer.phar /usr/local/bin/composer

### 安裝SchoolWeb
git clone https://github.com/wangchifu/SchoolWeb.git

進入 SchoolWeb
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan config:clear

### 建立資料庫
使用phpMyAdmin或下指令建一個資料庫

### 編輯 .env
- 資料庫名稱：DB_DATABASE=homestead
- 資料庫帳號：DB_USERNAME=homestead
- 資料庫密碼：DB_PASSWORD=secret
- 管理者帳號：ADMIN_USERNAME=admin
- 帳號前置：DEFAULT_USER_ACC=hd
- 預設密碼：DEFAULT_USER_PWD=demo1234
- 判斷校內文件IP：SCHOOL_IP=
- 登入方式：DEFAULT_LOGIN_TYPE=eloquent
(eloquent本機；adldap為Open Ldap)
- 使用Open Ldap登入時的相關資訊
  ADLDAP_ACCOUNT_PREFIX=uid=<br>
  ADLDAP_ACCOUNT_SUFFIX=,cn=users,dc=xxx,dc=xxx<br>
  ADLDAP_CONTROLLERS=your_address<br>
  ADLDAP_BASEDN=dc=xxx,dc=xxx<br>
  
### 建資料表
- php artisan migrate

### 建管理者
- php artisan db:seed