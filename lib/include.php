<?php
ini_set('max_execution_time', 300);
/**
 * it set the application id and application secret id
 */
$fb_app_id = '1666410266911684';
$fb_secret_id = '06090170170002699844c87d1b5b40cc';

$fb_login_url = 'http://localhost/xampp/fb/fb/src/index.php';

require_once ('Facebook/autoload.php');
/**
 *
 * define the namespace alies
 * for the use of facebook namespace
 * easy to use
 */
session_start();
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
/**
 * setting the application configuration
 * and session
 *
 */
FacebookSession::setDefaultApplication($fb_app_id, $fb_secret_id);
$helper = new FacebookRedirectLoginHelper($fb_login_url);
/**
 *
 * setting the session for our website
 */
if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
	$session = new FacebookSession($_SESSION['fb_token']);
	try {
		if (!$session -> validate()) {
			$session = null;
		}
	} catch ( Exception $e ) {
		$session = null;
	}
}
if (!isset($session) || $session === null) {
	try {
		$session = $helper -> getSessionFromRedirect();

	} catch( FacebookRequestException $ex ) {
		print_r($ex);
	} catch( Exception $ex ) {
		print_r($ex);
	}
}
function getdatafromfaceboook($url) {
   
	$session = new FacebookSession($_SESSION['fb_token']);
	$request_userinfo = new FacebookRequest($session, 'GET', $url);
	$response_userinfo = $request_userinfo -> execute();
	$userinfo = $response_userinfo -> getGraphObject() -> asArray();

	return $userinfo;
}
?>
