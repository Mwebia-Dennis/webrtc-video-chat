<!DOCTYPE html>
<html>
<head>
	 <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!---fontawesome---->
    <script src="https://kit.fontawesome.com/fe39f99d95.js" crossorigin="anonymous"></script>


	<title>Video Conferencing</title>

	<style type="text/css">

		html {
			width: 100%;
			height: 100%;
		}
		body {
			width: 100%;
			height: 100%;
			position: relative;
		}
		

		.video_page {
			display: inline-flex;
			width: 100%;
			height: 100%;
			position: relative;
		}
		.video_container {

			width: 70%;
			margin-left: 20px;
			position: relative;
		}

		.video {
			width: 200px;
			height: 200px;
			background-color: #000;
		}
		#video_container {

			margin-top: 20px;
		}

		.participant_prof {
			width: 50px;
			height: 50px;
			 border-radius: 50%;
			 margin: 10px;
			 border-bottom: 1px solid #d3d3d3;
		}

		.participants {
			width: 100%;
			height: 100%;
			padding: 10px;
			overflow-y: auto;
			display: none;
			z-index: 1;
			position: absolute;
			background: #fff;
		}
		.participants ul {width: 100%; margin-top: 15px;}
		.participants ul li {list-style-type: none;}
		.participants ul li a {color: black;}

		.fab {

			width: 50px;
			height: 50px;
			border-radius: 50%;
			margin: 10px;
			text-align: center;
			position: absolute;
			bottom: 10px;
			right: 2px;
			border: 1px solid #f33;
			background: #f33;
			color: #fff;
			display: block;
			z-index: 10;
		}

		.fab:hover {
			opacity: .8;
		}

		.friend_name {
			font-weight: bold;opacity: .8;font-size: 15px;margin-bottom: 20px;text-align: center;
			color: #0066ff;
		}


		.video_buttons {

			position: absolute;
			bottom: 20px;
			align-content: center;
			justify-content: center;
			z-index: 10;
		}
		.video_buttons button {

			width: 50px;
			height: 50px;
			margin-right: 5px;
			border-radius: 50%;
			background: #f33;
			color: #fff;
			font-size: 20px;
			text-align: center;
			border: 1px solid #f33;
		}

		.video_buttons button:hover {background: #000;opacity: .8;border-color: rgba(0,0,0,.8);}

		.info_box {

			position: absolute;
			top: 10px;
			width: 90%;
			right: 5%;
			padding: 5px 10px;
			border: 10px;
			display: none;
			text-align: center;

		}

		.info_box span{

			font-size: 25px;
			margin: 0 10px;
			color: #f33;
		}

		.info_box button {

			width: 25px;
			height: 25px;
			border-radius: 50%;
			border: 1px solid #d3d3d3;
			background-color: #fff;
			opacity: .8;
			text-align: center;
			font-size: 13px;
			position: absolute;
			top: 5px;
			right: 5px;
		}
		

	@media only screen and (min-width: 768px) {

		body {
			position: relative;
		}

		.video {
			width: 100%;
			height: 300px;
		}

		.participants {display: inline-block;position: static;}

		.fab {display: none !important;}

		.participants {
			width: 300px;
			border-right: 1px solid #d3d3d3;
			margin-right: 10px;
			margin-top: 10px;

		}

		.video_buttons {
			right: 40%;
			bottom: 40px;

		}

		.video_buttons button {

			width: 70px;
			height: 70px;
		}

		.info_box {
			right: 100px;
			width: 450px;
		}
	}

	</style>
</head>
<body>

	<div class="video_page" id="video_page" style="display: inline-flex;">

		<div class="participants" id="participants">

			<h4 style="text-align: center;font-weight: bold;color: #f33;font-size: 26px;text-decoration: underline;">Participants</h4>
			
			<ul>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
				<li><a href=""><img src="video.jpg" class="participant_prof">dennis gitonga</a></li>
			</ul>

		</div>

		<div class="video_container" id="video_container">
			<div class="row" id="list_of_videos">
			

				<div class="col-md-4">
					

					<video id="localVideo" class="video" autoplay="autoplay" muted></video>
				</div>			
				

			</div>


			<div class="video_buttons">
				
				<button class="media_icons"><i class="fas fa-microphone"></i></button>
				<button class="media_icons"><i class="fas fa-phone-slash"></i></button>
				<button class="media_icons"><i class="fas fa-video"></i></button>
			</div>
		</div>



		
		


		<button class="fab" onclick="toggleParticipants(this)"><i class="fas fa-users"></i></button>


	</div>


	<div class="info_box alert-info" id="alert_box">

		<span><i class="fas fa-spinner fa-spin"></i></span>
		<button onclick="closeAlert(this)"><i class="fas fa-times"></i></button>
		<p id="alert_content"></p>
</div>
	


	
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <script src="access_server.js"></script>

    

	<script src="webrtc.js"></script>



	<script type="text/javascript">
		
		function toggleParticipants(context) {

			$('#participants').toggle();

			if ($('#participants').css('display') == 'block') {

				$(context).find('i').removeClass('fa-users');
				$(context).find('i').addClass('fa-times');
			}else {
				$(context).find('i').removeClass('fa-times');
				$(context).find('i').addClass('fa-users');
			}
		}

		function closeAlert(ctx) {

			ctx.parentElement.style.display = 'none';
		}


	</script>

</body>
</html>