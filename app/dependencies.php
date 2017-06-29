<?php
use OCLC\Auth\WSKey;
use OCLC\User;
use Symfony\Component\Yaml\Yaml;

$app['config'] = Yaml::parse($config_file);

$app['wskey'] = function ($app) {
	if (isset($_SERVER['HTTPS'])):
	$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . "/catch_auth_code";
	else:
	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . "/catch_auth_code";
	endif;
	
	$services = array('WorldCatMetadataAPI');
	$options = array('services' => $services, 'redirectUri' => $redirect_uri);
	return new WSKey($app['config']['prod']['wskey'], $app['config']['prod']['secret'], $options);
};

$app['user'] = function ($app) {
	return new User($app['config']['prod']['institution'], $app['config']['prod']['principalID'], $app['config']['prod']['principalIDNS']);
};