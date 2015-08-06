# Vagrant

- enable at least 2G of memory for the VM
- forward mysql port, 3306
- vagrant up
- install docker
- docker pull mysql
- docker run -d --name mysql -v /var/lib/mysql:/var/lib/mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root mysql
- docker exec -it mysql /bin/bash
```bash
mysql -uroot -proot
CREATE DATABASE `humhub` CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'humhub'@'localhost' IDENTIFIED BY 'humhub';
GRANT ALL PRIVILEGES ON *.* TO 'humbub'@'localhost' WITH GRANT OPTION;
CREATE USER 'humhub'@'%' IDENTIFIED BY 'humhub';
GRANT ALL PRIVILEGES ON *.* TO 'humhub'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
\q
```

http://blog.frd.mn/install-nginx-php-fpm-mysql-and-phpmyadmin-on-os-x-mavericks-using-homebrew/

# Host

- xcode-select --install
- brew tap homebrew/dupes
- brew tap homebrew/php
- brew install php56 --without-apache --with-fpm --with-mysql --with-homebrew-apxs --with-homebrew-curl
- git clone --depth=1 https://github.com/hossamkarim/humhub.git
- add this file to `protected/config/local/_settings.php`
```php
<?php return array ( 'components'=>
  array (
    'urlManager' => array(
      'urlFormat' => 'path',
      'showScriptName' => false,
    ),
  )
);
```
- create nginx logging directory
```bash
mkdir -p /tmp/nginx/humhub/logs/
```

- start php-fpm
```bash
/usr/local/opt/php56/sbin/php56-fpm start
```

- start nginx with
```bash
nginx -c $PWD/humhub-nginx.conf
```


Note the missing library on starting humhub after the below steps: PHP - APC Support (Hint: Optional - Install APC Extension for APC Caching)

to install humhub:
------------------

brew install php56 --with-homebrew-apxs --with-homebrew-curl

brew install mysql

brew tap homebrew/apache

brew install httpd22

httpd -v

then to start mysql:
--------------------

mysqld

mysql -uroot

then create the database:
-------------------------

CREATE DATABASE `humhub` CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT ALL ON `humhub`.* TO `humhub_dbuser`@localhost IDENTIFIED BY 'password_changeme';
FLUSH PRIVILEGES;

then to start the server:
--------------------------

create the httpd.conf file with the below content and then run the server with the following command:

httpd -d . -f httpd.conf -DFOREGROUND

the httpd.conf:
--------------

#
# Minimal httpd.conf for running apache in the foreground for local php
# development.
#
# Setup:
# 1. Place this file in the root of your project.
# 2. Make sure the ./tmp directory exists (for the pid and lock files).
# 3. Update the DocumentRoot and Directory directives with the relative path to
#    your project's document root.
#
# Usage:
# httpd -d . -f httpd.conf -DFOREGROUND
#
# Relative file paths in this file are relative to the server root, which is
# assumed to be set from the command line option, as in the about usage.
#

ServerName localhost
Listen 8080
PidFile httpd.pid
LockFile accept.lock

#
# Optional Modules
#

# Provides allow, deny and order directives.
LoadModule authz_host_module /usr/local/Cellar/httpd22/2.2.29/libexec/mod_authz_host.so

# Provides DirectoryIndex directive.
LoadModule dir_module /usr/local/Cellar/httpd22/2.2.29/libexec/mod_dir.so

# Provides SetEnv directive.
LoadModule env_module /usr/local/Cellar/httpd22/2.2.29/libexec/mod_env.so

# Provides automatic mime content type headers.
LoadModule mime_module /usr/local/Cellar/httpd22/2.2.29/libexec/mod_mime.so

# Provides CustomLog and LogFormat directives.
LoadModule log_config_module /usr/local/Cellar/httpd22/2.2.29/libexec/mod_log_config.so

# Allow rewrite rules in .htaccess files.
LoadModule rewrite_module /usr/local/Cellar/httpd22/2.2.29/libexec/mod_rewrite.so

# Using homebrew php53.  Change as necessary.
LoadModule php5_module /usr/local/opt/php56/libexec/apache2/libphp5.so

#
# Logs are piped to `cat` which prints to STDOUT.
#
LogLevel info
ErrorLog "|cat"
LogFormat "%h %l %u %t \"%r\" %>s %b" common
CustomLog "|cat" common

#
# Since this is intended for local environments, the single document root is
# highly permissive.
#
DocumentRoot "/Users/rhelal/Worklize/humhub-0.11.2"
<Directory "/Users/rhelal/Worklize/humhub-0.11.2">
  AllowOverride all
  Order allow,deny
  Allow from all
</Directory>

#
# Basic PHP handling.
#
AddType application/x-httpd-php .php
DirectoryIndex index.html index.php

#
# Provide applications with a hook to detect when running locally.
#
SetEnv LOCAL_SERVER true


then start the application:
 ---------------------------
 on safari: "http://localhost/"

and follow the steps with considering that the host value is "127.0.0.1"
