<?php
require_once(__DIR__ . '/../vendor/autoload.php');
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

$app = new Application();

$app->register(new TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/views',
));

// Set up dependencies
require __DIR__ . '/dependencies.php';
// Register middleware
require __DIR__ . '/middleware.php';
// Register routes
require __DIR__ . '/routes.php';
	
return $app;

