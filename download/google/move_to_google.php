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
ini_set('max_execution_time', 300);
session_start();

require_once                 realpath(dirname(__FILE__) . '/libs/src/Google/autoload.php');

/************************************************
 We'll setup an empty 1MB file to upload.
 ************************************************/
/************************************************
 ATTENTION: Fill in these values! Make sure
 the redirect URI is to this page, e.g:
 http://localhost:8080/fileupload.php
 ************************************************/
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/xampp/fb/fb/download/google/move_to_google.php';
if (isset($_GET['album_download_directory'])) {
	$album_download_directory = $_GET['album_download_directory'];
	$album_download_directory = '../' . $album_download_directory;
} else {
	header('location:../index.php');
}
$client = new Google_Client();
$client -> setAuthConfigFile('client_secret.json');
$client -> setRedirectUri($redirect_uri);
$client -> addScope("https://www.googleapis.com/auth/drive", "https://www.googleapis.com/auth/drive.appfolder");
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
	if ($client -> isAccessTokenExpired()) {
		unset($_SESSION['upload_token']);
	}
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
if (isset($album_download_directory)) {
	global $service;

	if (file_exists($album_download_directory)) {
		$album_names = scandir($album_download_directory);
		foreach ($album_names as $album_name) {
			if ($album_name != "." && $album_name != "..") {
				add_new_album($album_download_directory, $album_name);
				echo "hello";
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
			echo "<a class='login' href='" . $authUrl . "'>Connect Me!</a>";
		}
		?>
	</div>

	<div class="shortened">
		<?php
		if (isset($result) && $result) {
			var_dump($result -> title);
			var_dump($result2 -> title);
		}
		?>
	</div>
</div>
<?php