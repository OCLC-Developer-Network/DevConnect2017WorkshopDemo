<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

$auth_mw = function (Request $request, Silex\Application $app, $oclcnumber = null) {
	if ($oclcnumber){
		$_SESSION['route'] = $app['url_generator']->generate($request->get("_route"), array('oclcnumber' => $oclcnumber));
	} else {
		$oclcnumber = $request->get("oclcnumber");
		$_SESSION['route'] = $app['url_generator']->generate($request->get("_route"), array('oclcnumber' => $oclcnumber));
	}
	
	if (empty($_SESSION['accessToken']) || ($_SESSION['accessToken']->isExpired() && (empty($_SESSION['accessToken']->getRefreshToken()) || $_SESSION['accessToken']->isExpired()))){
		$redirect = $app['wskey']->getLoginURL($app['config']['prod']['institution'], $app['config']['prod']['institution']);
		return new RedirectResponse($redirect);
	} 
};