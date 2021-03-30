<?php

//ini_set('error_reporting', ~E_WARNING);

//error_reporting(0);
date_default_timezone_set('America/Sao_Paulo');

define('APP_NAME', 'PHPSTORE');
define('APP_VERSION', '1.0.0');

define('BASE_URL', "http://" . $_SERVER["HTTP_HOST"] . "/");

define('EMAIL_HOST', 'smtp.gmail.com');
define('EMAIL_FROM', 'lojaphpstore@gmail.com');
define('EMAIL_PASS', '0123!@789');
define('EMAIL_PORT', 587);

define('MYSQL_SERVER', 'localhost');
define('MYSQL_DATABASE', 'php_store');
define('MYSQL_USER', 'user_php_store');
define('MYSQL_PASS', '!user_php_store@2021');
define('MYSQL_CHARSET', 'utf8');