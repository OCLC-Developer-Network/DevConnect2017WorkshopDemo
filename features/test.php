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

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/../app/views',
));

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';
// Register middleware
require __DIR__ . '/../app/middleware.php';
// Register routes
require __DIR__ . '/../app/routes.php';
	
// Run application
$app->run();

