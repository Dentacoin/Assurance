<?php

//define PATHS
define('DS', '/');
define('ROOT', realpath(dirname(__FILE__)) . DS);
//define('BASE_URL', 'https://dev-test.dentacoin.com/');
define('BASE_URL', 'https://assurance.dentacoin.com/');
define('UPLOADS', ROOT . 'public' . DS . 'assets' . DS . 'uploads' . DS);
define('CONTRACTS', ROOT . 'public' . DS . 'assets' . DS . 'contracts' . DS);
define('ZIP_EXTRACTS', ROOT . 'public' . DS . 'assets' . DS . 'zip-extracts' . DS);
define('UPLOADS_FRONT_END', DS . 'assets' . DS . 'uploads' . DS);

//define variables used in the logic
define('MAX_UPL_SIZE', 2097152);
define('EMAIL_SENDER', 'assurance@dentacoin.com');
define('DAYS_ACTIVE_CONTRACT_PROPOSAL', 30);
