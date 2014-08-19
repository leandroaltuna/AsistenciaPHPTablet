<?php

ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('BASEPATH', ROOT . 'system' . DS);
define('APP_PATH', ROOT . 'application' . DS);

require_once BASEPATH . 'core' . DS . 'Config.php';
require_once BASEPATH . 'core' . DS . 'Autoload.php';

try {

    Bootstrap::run(Request::getInstance());
} catch (Exception $e) {
    echo $e->getMessage();
}
?>