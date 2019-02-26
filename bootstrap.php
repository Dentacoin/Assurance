<?php
// define short code for DIRECTORY_SEPARATOR
define('DS', '/');
// define base directory for project
define('ROOT', realpath(dirname(__FILE__)) . DS);
// define uploads directory
define('UPLOADS', ROOT . 'public' . DS . 'assets' . DS . 'uploads' . DS);
define('CONTRACTS', ROOT . 'public' . DS . 'assets' . DS . 'contracts' . DS);
define('ZIP_EXTRACTS', ROOT . 'public' . DS . 'assets' . DS . 'zip-extracts' . DS);
define('UPLOADS_FRONT_END', DS . 'assets' . DS . 'uploads' . DS);
define('MAX_UPL_SIZE', 2097152);
define('EMAIL_SENDER', 'assurance@dentacoin.com');

var_dump(ROOT);
die();