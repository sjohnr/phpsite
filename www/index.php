<?php

define('ROOT_DIR',    realpath(dirname(__file__).'/..'));
define('APP_NAME',    'test');
define('ENVIRONMENT', 'dev'); // or 'prod'
define('IS_DEBUG',    true);

// bootstrap the framework
include(ROOT_DIR.'/bootstrap.php');

// dispatch the request
Controller::getInstance()->dispatch();

?>
