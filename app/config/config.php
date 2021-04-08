<?php

$host = "http://localhost";
$rootFolder = "otrium";
$siteName = "Otrium Challenge";
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "otrium_challenge";

//ROOT FOLDER
define('ROOT_FOLDER', $rootFolder);

// SITE URL
define('SITE_URL', $host . '/' . ROOT_FOLDER);

//SITE NAME
define('SITE_NAME', $siteName);

// DB PARAMS
define('DB_HOST', $dbHost);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_NAME', $dbName);

// APP ROOT
define('APP_ROOT', dirname(dirname(__FILE__)));

// DOCUMENT ROOT
define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);


// LIBRARAY PATH
define('LIB_PATH', DOC_ROOT . '/'.ROOT_FOLDER . '/vendor');

// PUBLIC URL
define('PUB_URL', DOC_ROOT . '/' . ROOT_FOLDER . '/public');

