<?php
require_once('vendor/autoload.php');
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
session_start();

// instantiate the App object
global $config_file;
$config_file = file_get_contents(__DIR__ . '/config/config.yml');

$app = require __DIR__ . '/app/app.php';
	
// Run application
$app->run();

