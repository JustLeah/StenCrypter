<?php
require_once('common.php');
?>
<!DOCTYPE html>
<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<link rel="stylesheet" type="text/css" href="css/dropzone.css">
	<script src="scripts/dropzone.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script type="text/javascript">
		
		$( document ).ready(function() {
			resizeMe();
		});

		function resizeMe(){
		 height = $(window).height();
		 width = $(window).width();
		 $("html").css('width', width + "px");
		 $("html").css('height', height - 9 + "px");
		 $("#main-container").css('height', height - 59 + "px");
		}

		function loadPage(pageToLoad){
		loadingGif();	
		$.ajax({
		   type: "POST",
		   url: pageToLoad,  
		   data:"id=test",
		   success: function loadData(data){
				$('#feature-container').html(data);
			   }
		   });
		}

		function loadingGif(){
			code = "<div id='loading-gif'> <img src='images/loading.gif' /> </div>";
			$('#feature-container').html(code);
		}

		$(window).resize(function () {
		    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			 // some code..
			}else{
				resizeMe();
			}
		});
	</script>
	<link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="main-container">
			<div id="title">
				<span style="color:#60D9F1">STEN</span><span style="color:#FC1E70;">CRYPTER</span>
			</div>
			<div id="strapline">
				The hidden in plain sight encryption tool
			</div>
			<div id="feature-container">
				<div id="feature-title">
					Please select what you would like to do below.
				</div>
				<a onclick="loadPage('encrypt.php'); return false;">
				<div class="button">
					<p>
						<span style="color:#FC1E70">E</span>NCRYPT <span style="color:#FC1E70">T</span>EXT
					</p>
				</div>
				</a>
				<a onclick="loadPage('decrypt.php'); return false;">
				<div class="button">
					<p>
						<span style="color:#FC1E70">D</span>ECRYPT <span style="color:#FC1E70">T</span>EXT
					</p>
				</div>
				
				</a>
			</div>
			
		</div>	
		<div id="footer">
			<a onclick="loadPage('home-ajax.php'); return false;">
				<div id="home-icon">
					<img src="images/home-icon.png" />
				</div>
			</a>
			<div id="footer-logo">
				<a href="https://github.com/hristogg1/StenCrypter" target="_blank"><img src="images/github.png" /></a>
			</div>
			<div id="footer-text">
				Fork us and contribute on Github!
			</div>
			<div id="preload-01"></div>
		</div>
	</body>
</html>