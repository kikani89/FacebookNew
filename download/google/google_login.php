<?php
 ini_set('display_errors', 1); 
error_reporting(E_ALL);
 
ini_set('max_execution_time', 300);
session_start();
//response.setHeader("Access-Control-Allow-Origin", "*");
require_once  'libs/src/Google/autoload.php';

/************************************************
 We'll setup an empty 1MB file to upload.
 ************************************************/
/************************************************
 ATTENTION: Fill in these values! Make sure
 the redirect URI is to this page, e.g:
 http://localhost:8080/fileupload.php
 ************************************************/
$redirect_uri = 'https://facebookchallange.herokuapp.com/download/google/move_to_google.php';

$client = new Google_Client();
$client -> setAuthConfigFile('client_secret.json');
$client -> setRedirectUri($redirect_uri);
$client -> addScope("https://www.googleapis.com/auth/drive", "https://www.googleapis.com/auth/drive.appfolder");
$client->setIncludeGrantedScopes(true);
$service = new Google_Service_Drive($client);

if (isset($_REQUEST['logout'])) {
	unset($_SESSION['upload_token']);
}

if (isset($_GET['code'])) {
	$client -> authenticate($_GET['code']);
	$_SESSION['upload_token'] = $client -> getAccessToken();
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['upload_token']) && $_SESSION['upload_token']) {
	$client -> setAccessToken($_SESSION['upload_token']);
	 $sessionToken = json_decode($_SESSION['upload_token']);
    //Save the refresh token (object->refresh_token) into a cookie called 'token' and make last for 1 month
    if (isset($sessionToken->refresh_token)) { //refresh token is only set after a proper authorisation
        $number_of_days = 30 ;
        $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days ;
        setcookie('upload_token', $sessionToken->refresh_token, $date_of_expiry);
    }
	//if ($client -> isAccessTokenExpired()) {
		//unset($_SESSION['upload_token']);
	//}
}
else if (isset($_COOKIE["upload_token"])) {//if we don't have a session we will grab it from the cookie
    $client->refreshToken($_COOKIE["upload_token"]);//update token
}

if ($client->getAccessToken()) {
   // print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
    $_SESSION['upload_token'] = $client->getAccessToken();
} else {
	$authUrl = $client -> createAuthUrl();
}
?>