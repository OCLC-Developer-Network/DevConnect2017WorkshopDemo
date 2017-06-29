<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
// Add routes

//display form
$app->get('/', function (Silex\Application $app) {
	return $app['twig']->render('search_form.html');
})->bind('display_search_form');

//display bib route
$app->get('/bib/{oclcnumber}', function ($oclcnumber, Silex\Application $app, Request $request){
	$_SESSION['route'] = $app['url_generator']->generate($request->get("_route"), array('oclcnumber' => $oclcnumber));
	if (empty($oclcnumber)){
		return $app['twig']->render('error.html', [
				'error' => 'No OCLC Number present',
				'error_description' => 'Sorry you did not pass in an OCLC Number',
				'oclcnumber' => null
		]);
	}
	$bib = Bib::find($oclcnumber, $_SESSION['accessToken']);
	
	if (is_a($bib, "Bib")){
		
		return $app['twig']->render('bib.html', [
				'bib' => $bib
		]);
	}else {
		return $app['twig']->render('error.html', [
				'error' => $bib->getStatus(),
				'error_message' => $bib->getMessage(),
				'oclcnumber' => $oclcnumber
		]);
	}
})->bind('display_bib')->before($auth_mw);

//display bib route used for searching
$app->match('/bib', function (Silex\Application $app, Request $request){
	$oclcnumber = $request->get('oclcnumber');
	$_SESSION['route'] = $app['url_generator']->generate($request->get("_route"), array('oclcnumber' => $request->get('oclcnumber')));
	if (empty($oclcnumber)){
		return $app['twig']->render('error.html', [
				'error' => 'No OCLC Number present',
				'error_description' => 'Sorry you did not pass in an OCLC Number',
				'oclcnumber' => null
		]);
	}
	$bib = Bib::find($oclcnumber, $_SESSION['accessToken']);
	
	if (is_a($bib, "Bib")){
		
		return $app['twig']->render('bib.html', [
				'bib' => $bib
		]);
	}else {
		return $app['twig']->render('error.html', [
				'error' => $bib->getStatus(),
				'error_message' => $bib->getMessage(),
				'oclcnumber' => $oclcnumber
		]);
	}
})->method('GET|POST')->bind('bib_search_results')->before($auth_mw);

$app->get('/catch_auth_code', function (Silex\Application $app, Request $request) {
	if (isset($_SESSION['route'])){
		$route = $_SESSION['route'];
	} else {
		$route = '/';
	}
	
	if ($request->get('code')){
		try{
			$_SESSION['accessToken'] = $app['wskey']->getAccessTokenWithAuthCode($request->get('code'), $app['config']['prod']['institution'], $app['config']['prod']['institution']);
			return $app->redirect($route);
		} catch(Exception $e) {
			return $app['twig']->render('error.html', [
					'error' => $e->getMessage(),
					'error_description' => '',
					'oclcnumber' => null
			]);
		}
	}elseif ($request->get('error')){
		return $app['twig']->render('error.html', [
				'error' => $request->get('error'),
				'error_description' => $request->get('error_description'),
				'oclcnumber' => null
		]);
	}else {
		return $app->redirect($route);
	}
})->bind('catch_auth_code');

$app->get('/logoff', function (Silex\Application $app){
	session_destroy();
	return $app->redirect('/');
})->bind('logoff');