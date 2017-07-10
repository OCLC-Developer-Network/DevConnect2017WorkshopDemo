<?php
require_once(__DIR__ . '/../vendor/autoload.php');
\VCR\VCR::turnOn();
\VCR\VCR::configure()->setCassettePath(__DIR__ . '/mocks');
\VCR\VCR::configure()->enableRequestMatchers(array('method', 'url', 'host'));
\VCR\VCR::insertCassette('test_mocks');

session_start();
// instantiate the App object
global $config_file;
$config_file = file_get_contents(__DIR__ . '/../app/config/test_config.yml');

$app = require __DIR__ . '/../app/app.php';
$app['debug'] = true;
	
// Run application
$app->run();

