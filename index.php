<?php
require_once('vendor/autoload.php');
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
session_start();

// instantiate the App object
global $config_file;
$config_file = file_get_contents(__DIR__ . '/app/config/config.yml');

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/app/views',
));

// Set up dependencies
require __DIR__ . '/app/dependencies.php';
// Register middleware
require __DIR__ . '/app/middleware.php';
// Register routes
require __DIR__ . '/app/routes.php';
	
// Run application
$app->run();

