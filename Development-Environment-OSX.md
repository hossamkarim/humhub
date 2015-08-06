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
