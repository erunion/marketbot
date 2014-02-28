<?php

// When you use @runInSeparateProcess the bootstrap file is rerun
// if it wasn't for this check, there would be a php error trying
// to redefine these constants.
/*if (!defined('TEST_ASSETS')) {
    define('TEST_ASSETS', getcwd() . '/Tests/Assets');
}

if (!defined('TEST_APP_ROOT')) {
    define('TEST_APP_ROOT', TEST_ASSETS . '/app');
}*/

/** @var $composer Composer\Autoload\ClassLoader */
$composer = require 'vendor/autoload.php';

//$composer->add('TestPlugins\\', TEST_ASSETS);
//$composer->add('Test\\', TEST_ASSETS);
