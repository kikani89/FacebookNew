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

/************************************************
 If we're signed in then lets try to upload our
 file. For larger files, see fileupload.php.
 ************************************************/
require_once 'google_login.php';
 if ($client -> getAccessToken()) {
	
	$file = new Google_Service_Drive_DriveFile();
	
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
if (isset($_GET['album_download_directory'])) {
		$album_download_directory = $_GET['album_download_directory'];
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