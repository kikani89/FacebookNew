<html>
<head>
<title>::Slide::</title>
    <?php
        use Facebook\FacebookSession;
		use Facebook\FacebookRedirectLoginHelper;
		use Facebook\FacebookRequest;
		use Facebook\FacebookResponse;
		use Facebook\FacebookSDKException;
		use Facebook\FacebookRequestException;
		use Facebook\FacebookAuthorizationException;
		use Facebook\GraphObject;
        include('../lib/include.php');
	?>
        <script type="text/javascript" src="../lib/js/jquery-1.10.0.min.js"></script>
        <script type="text/javascript" src="../lib/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../lib/js/bootstrap.js"></script>
        <link rel="stylesheet" href="../lib/css/bootstrap.css" />
        <link rel="stylesheet" href="../lib/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../lib/css/bootstrap-theme.css" />
        <script type="text/javascript" src="../lib/js/jquery.carousel.fullscreen.js"></script>
</head>
<body>
<div >
<a href="index.php" style="position: fixed;top: 5px;left: 90%;z-index: 999;width: 150px;height: 23px;">
  <span class="glyphicon glyphicon-remove " ></span></a>
</div>
    <div id="carousel-generic" class="carousel slide" data-ride="carousel">
    <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox" style="align-items: center;margin-left: 15% ;">
        <?php
    	    if ( isset( $session ) )
		    {
			    $_SESSION['fb_login_session'] = $session;
    			$_SESSION['fb_token'] = $session->getToken();
    			$session = new FacebookSession( $session->getToken() );
    			$alb_id=$_GET['ida'];
    			$request_album_photo=new FacebookRequest($session,'GET','/'.$alb_id.'/photos?fields=source');
    			$response_album_photo=$request_album_photo->execute();
    			$album_photo=$response_album_photo->getGraphObject()->asArray();
    			if(!empty($album_photo))
    			{
			        $f=0;
					foreach($album_photo['data'] as $alb)
					{
						$alb=(array) $alb;
                        if($f==0)
                        {
                            echo '<div class="item active">';
                            echo "<img class='img' src='".$alb['source']."'/>";
                            echo '</div>';
                            $f=1;
                        }
                        else
                        {
                            echo '<div class="item">';
                            echo "<img class='img' src='".$alb['source']."'/>";
                            echo '</div>';
                        }
					}
    			}
		    }
        ?>
        </div>
  </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-generic" role="button" data-slide="prev">
         <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
         <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>

  </body>
</html>
