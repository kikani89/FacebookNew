
<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>:: Welcome To Facebook Challenge ::</title>
<script type="text/javascript" src="../lib/js/jquery-1.10.0.min.js"></script>
<link rel="stylesheet" href="../lib/css/bootstrap.css" />
<link rel="stylesheet" href="../lib/css/bootstrap.min.css" />
<link rel="stylesheet" href="../lib/css/style.css" />
<script type="text/javascript" src="../lib/js/bootstrap.js"></script>
<script type="text/javascript" src="../lib/js/bootstrap.min.js"></script>




</head>

<body>
<?php
/**
 * it set the all configuration for the facebook app
 */

include_once ("../lib/include.php");
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
  echo "hello";
try {
	if (isset ( $session )) {
		$_SESSION ['fb_login_session'] = $session;
		$_SESSION ['fb_token'] = $session->getToken ();
		
		$userinfo = getdatafromfaceboook ( "/me" );
		$_SESSION ['user_id'] = $userinfo ['id'];
		$_SESSION ['username'] = $userinfo ['name'];
		
		?>
		
    <div class="container" style="margin-top: 5px; padding-top: 0px">
		<div class="header " style="padding-bottom: 5px">
			<nav>
			
			
			<ul class="nav nav-pills pull-right">
				<li class="" role="presentation"><span><img class="img-circle"
						src="https://graph.facebook.com/<?php echo $userinfo['id']; ?>/picture"
						style="margin-right: 10px" alt="" /> <label
						style="margin-right: 10px"><?php echo $userinfo['name']; ?></label>
				</span></li>
				<li class="active"><a href="logout.php">Logout</a></li>
			</ul>
			</nav>
			<h2 class="text-muted">Wel-Come To Facebook Challenge</h2>
		</div>
		<hr>
			<hr>
				<div class="modal fade" id="download-modal" tabindex="-1"
					role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">
									<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">Albums Report</h4>
							</div>
							<div class="modal-body" id="display-response">
								<!-- Response is displayed over here -->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default"
									data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<div class=" progress-container" >
    				<div class="progress progress-striped active">
        				<div class="progress-bar progress-bar-success" style="width:5%"></div>
    				</div>
				</div>
				<nav class=" navbar navbar-inverse">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed"
						data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span>
					</button>
					<span class="navbar-brand">Albums</span>
				</div>
				<div class="collapse navbar-collapse"
					id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="#" id="download-all-albums">Download All </a></li>
						<li><a href="#" id="move_all">Move All</a></li>
						<li><a href="#" id="download-selected-albums">Download Selected</a></li>
						<li><a href="#" id="move-selected-albums">Move Selected</a></li>
					</ul>
				</div>
				</nav>
				<div class="row">
      <?php
		$albums = getdatafromfaceboook ( "/me/albums" );
		
		if (! empty ( $albums )) {
			
			foreach ( $albums ['data'] as $album ) {
				$album = ( array ) $album;
				
				$album_photo = getdatafromfaceboook ( '/' . $album ['id'] . '/photos?fields=source' );
				if (! empty ( $album_photo )) {
					foreach ( $album_photo ['data'] as $alb ) {
						$alb = ( array ) $alb;
					}
					
					?>
                    <div class="col-sm-5 col-md-2">
						<span> <a href="home.php?ida=<?php echo $album['id']; ?>"><?php echo $album['name']; ?></a>
						</span>

						<div class='thumbnail'
							style='overflow: hidden; height: 170px; width: 190px; padding-bottom: 2%;'>
							<div
								style='overflow: hidden; height: 160px; width: 180px; padding-bottom: 2%;'>
								<a href="slide.php?ida=<?php echo $album['id']; ?>"> <img
									style='box-shadow: 2px 2px 1.5px 1.5px #9d9d9d; position: relative;'
									src="<?php  echo $alb['source']; ?> " /></a>
							</div>
						</div>
						<div class="form-control input-group" style="margin-left: 2%;">
							<span class="input-group-addon input-sm"> <input
								class="select-album" type="checkbox"
								value="<?php echo $album['id'] . ',' . $album['name']; ?>" />
							</span>
							<button rel="<?php echo $album['id'] . ',' . $album['name']; ?>"
								class="single-download btn btn-default btn-sm"
								title="Download Album">
								<span class="glyphicon glyphicon-save" aria-hidden="true"></span>
							</button>
							<button type="button" class="move-single-album btn btn-default btn-sm" rel="<?php echo $album['id'] . ',' . $album['name']; ?>">
								<span class="glyphicon glyphicon glyphicon-share"
									aria-hidden="true">Move</span>
							</button>
						</div>
					</div>
                      <?php
		}
		}
		}
		} else {
		?>        
            <div class="container"
						style="margin-top: 5px; padding-top: 0px">
						<div class="header " style="padding-bottom: 5px">
							<nav>
							<ul class="nav nav-pills pull-right">

								<li class="active"><a
									href="<?php echo $helper -> getLoginUrl(array("user_photos", "public_profile")); ?>">Login</a></li>
							</ul>
							</nav>
							<h2 class="text-muted">Wel-Come To Facebook Challenge</h2>
						</div>
						<hr>
							<hr>
					
					</div><?php
					}
					} catch ( Exception $ex ) {
					echo $ex;
					}
				?>
  </div>

</body>
<script type="text/javascript">
	$(".progress-container").hide();
	function showProgress() {
		$(".progress-bar").css("width", "5%").attr("aria-valuenow", 0);
		var $progressBar = $('.progress-bar');
		$(".progress-container").show();
		setTimeout(function() {
			$progressBar.css('width', '10%');
			setTimeout(function() {
				$progressBar.css('width', '30%');
				setTimeout(function() {
					$progressBar.css('width', '50%');
					setTimeout(function() {
						$progressBar.css('width', '70%');
						setTimeout(function() {
							$progressBar.css('width', '80%');
						});
					}, 1000);
					// WAIT 5 milliseconds
				}, 3000);
				// WAIT 2 seconds
			}, 3000);
			// WAIT 1 seconds
		}, 5000);
	}

	function append_download_link(url) {

		$.ajax({
			url : url,
			success : function(result) {
				$(".progress-bar").animate({
					width : "100%"
				}, 500);

				$("#display-response").html(result);
				$("#download-modal").modal({
					show : true

				});
				setTimeout(function() {
					$(".progress-container").hide();
					//$(".progress-Bar").css('width', '10%');

				}, 2000);

			}
		});
	}


	$("#download-all-albums").on("click", function() {
		showProgress();
		alert("download sent");
		append_download_link("../lib/download_album.php?zip=1&all_albums=all_albums");

	});
	//single download
	$(".single-download").on("click", function() {
		showProgress();
		var rel = $(this).attr("rel");
		var album = rel.split(",");

		append_download_link("../lib/download_album.php?zip=1&single_album=" + album[0] + "," + album[1]);
	});

	//get selected data

	function get_all_selected_albums() {
		showProgress();
		var selected_albums;
		var i = 0;
		$(".select-album").each(function() {
			if ($(this).is(":checked")) {
				if (!selected_albums) {
					selected_albums = $(this).val();
				} else {
					selected_albums = selected_albums + "/" + $(this).val();
				}
			}
		});

		return selected_albums;
	}

	//selected data
	$("#download-selected-albums").on("click", function() {
		showProgress();
		var selected_albums = get_all_selected_albums();
		append_download_link("../lib/download_album.php?zip=1&selected_albums=" + selected_albums);
	});

	function move_to_picasa(param1, param2) {

			$.ajax({
				url : "../lib/download_album.php?ajax=1&" + param1 + "=" + param2,
				success : function(result) {
				}
			});
	}
	$(".move-single-album").on("click", function() {
		var single_album = $(this).attr("rel");
		  alert('I clicked move single');
		move_to_picasa("single_album", single_album);
	});

	$("#move-selected-albums").on("click", function() {
		var selected_albums = get_all_selected_albums();
		  alert('I clicked selecteed');
		move_to_picasa("selected_albums", selected_albums);
	});

	$("#move_all").on("click", function() {
		  alert('I clicked All');
		move_to_picasa("all_albums", "all_albums");
	});

</script>
</html>
