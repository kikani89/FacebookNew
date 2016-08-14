<!DOCTYPE html>
<html>
	<head>
		<meta name="google-site-verification" content="ShOOjE4BmnzEDPvIElOMCd8MigR1k4R9mErQ3GkBMWU" />

	</head>
	<body>

	</body>
</html>
<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

ini_set('max_execution_time', 300);
session_start();
//response.setHeader("Access-Control-Allow-Origin", "*");
require_once 'libs/src/Google/autoload.php';

/************************************************
 We'll setup an empty 1MB file to upload.
 ************************************************/
/************************************************
 ATTENTION: Fill in these values! Make sure
 the redirect URI is to this page, e.g:
 http://localhost:8080/fileupload.php
 ************************************************/

function add_new_album($album_download_directory, $album_name) {
	global $service;
	$new_album_name = str_replace(" ", "_", $album_name);
	$new_album_name = $new_album_name . '_' . uniqid();

	$fileMetadata = new Google_Service_Drive_DriveFile( array('name' => $new_album_name, 'mimeType' => 'application/vnd.google-apps.folder'));
	$folder = $service -> files -> create($fileMetadata, array('fields' => 'id', 'mimeType' => 'application/vnd.google-apps.folder'));
	$folderId = $folder -> id;

	$path = $album_download_directory . $album_name;
	if (file_exists($path)) {
		$photos = scandir($path);
		foreach ($photos as $photo) {
			if ($photo != "." && $photo != "..") {
				$photo_path = $path . '/' . $photo;
				add_new_photo_to_album($photo, $photo_path, $new_album_name, $folderId);
			}
		}
	}
}

if (isset($_GET['album_download_directory'])) {
	$album_download_directory = $_GET['album_download_directory'];
	$redirect_uri = 'https://facebookchallange.herokuapp.com/download/google/move_to_google.php?album_download_directory =' . $album_download_directory;

	$client = new Google_Client();
	$client -> setAuthConfigFile('client_secret.json');
	$client -> setRedirectUri($redirect_uri);
	$client -> addScope("https://www.googleapis.com/auth/drive", "https://www.googleapis.com/auth/drive.appfolder");
	$client -> setIncludeGrantedScopes(true);
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
		if (isset($sessionToken -> refresh_token)) {//refresh token is only set after a proper authorisation
			$number_of_days = 30;
			$date_of_expiry = time() + 60 * 60 * 24 * $number_of_days;
			setcookie('upload_token', $sessionToken -> refresh_token, $date_of_expiry);
		}
		//if ($client -> isAccessTokenExpired()) {
		//unset($_SESSION['upload_token']);
		//}
	} else if (isset($_COOKIE["upload_token"])) {//if we don't have a session we will grab it from the cookie
		$client -> refreshToken($_COOKIE["upload_token"]);
		//update token
	}

	if ($client -> getAccessToken()) {
		// print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
		$_SESSION['upload_token'] = $client -> getAccessToken();
	} else {
		$authUrl = $client -> createAuthUrl();
	}

	/************************************************
	 If we're signed in then lets try to upload our
	 file. For larger files, see fileupload.php.
	 ************************************************/
	if ($client -> getAccessToken()) {

		$file = new Google_Service_Drive_DriveFile();

	}
	//$album_download_directory = '../' . $album_download_directory;
} else {
	header('location:/src/index.php');
}

if (isset($album_download_directory)) {
	global $service;

	if (file_exists($album_download_directory)) {
		$album_names = scandir($album_download_directory);
		foreach ($album_names as $album_name) {
			if ($album_name != "." && $album_name != "..") {
				add_new_album($album_download_directory, $album_name);
			}
		}
		$unlink_folder = rtrim($album_download_directory, "/");
		require_once ('../unlink_directory.php');
		$unlink_directory = new unlink_directory();
		$unlink_directory -> remove_directory($unlink_folder);
	}
	$response = 1;
} else {
	$response = 0;

}
function add_new_photo_to_album($photo, $path, $new_album_name, $folderId) {
	global $service;
	$file_name = $path;
	$fileMetadata = new Google_Service_Drive_DriveFile( array('name' => $photo, 'parents' => array($folderId)));
	$result = $service -> files -> create($fileMetadata, array('data' => file_get_contents($path), 'mimeType' => 'image/jpg', 'uploadType' => 'media'));
}
?>
<div class="box">
	<div class="request">
		<?php
		if (isset($authUrl)) {
			header("location: $authUrl ");
		}
		?>
	</div>
</div>