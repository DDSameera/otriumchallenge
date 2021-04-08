<?php

$rootFolder = "otrium";
$siteName = "Otrium Challenge";

// DB params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'otrium_challenge');

// App root
define('APPROOT', dirname(dirname(__FILE__)));

//Web Root
define('WEBROOT', dirname(dirname(basename($_SERVER['REQUEST_URI']))));

//document Root
define('DOCROOT', $_SERVER['DOCUMENT_ROOT']);

// SERVER URL
define('URLROOT', 'http://localhost/' . $rootFolder);

// Site name
define('SITENAME', $siteName);

// Library Path
define('LIBPATH', DOCROOT . "/$rootFolder/vendor");

//Public URL
define('PUBURL', DOCROOT . "/$rootFolder/public/");

