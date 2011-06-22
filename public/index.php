<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../library/vendor'),
    get_include_path(),
)));

/* Zend_Loader_Autoloader */
require_once 'Zend/Loader/Autoloader.php';

/** Zend_Application */
require_once 'Zend/Application.php';

// TODO: Refactor into cached instance
// Create application configuration
$applicationConfig = array(
    'config' => array(APPLICATION_PATH . '/configs/application.ini')
);

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV, $applicationConfig);

$application->bootstrap()
            ->run();