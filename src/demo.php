<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>insert page</title></head>
<?php
include_once ("../lib/include.php");
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;


	$session = new FacebookSession($_SESSION['fb_token' ]);
	try {
		
	$request_userinfo = new FacebookRequest($session, 'GET', '/me/albums');
        
	$response_userinfo = $request_userinfo -> execute();
       
	$userinfo = $response_userinfo -> getGraphObject() ;
        $albums=$userinfo->getProperty('data');
        var_dump($userinfo);
	echo "<pre>";
        
        print_r($userinfo);
        echo "</pre>";
	} catch (exception $ex) {
	print_r($ex);
	}
?>
</html> 
