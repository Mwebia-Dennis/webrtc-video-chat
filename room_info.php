
<?php


	if (!isset($_GET['room'])) {
		
		header('location: index.html');
		exit();

	}

?>
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
	<title>Room details</title>

	<style type="text/css">

		.box {

			width: 90%;
			margin: 10px;
			background: #fff;
		}
		
		.room_details {

			padding: 15px 10px;
		}
		.meeting_icon {

			width: 120px;
			height: 120px;
			border-radius: 50%;
			border: 1px solid #f33;
			text-align: center;
			font-size: 75px;
			background: #f33;
			margin-left: 20%;
			color: #fff;
			font-weight: bold;
			text-transform: uppercase;
		}

		.room_details h3 {

			font-weight: bold;
			color: #4d4d4d;
			font-size: 20px;
			margin: 15px 0;
		}

		.room_details p {

			font-size: 15px;
			color: #000;
		}

		.room_details p i{
			margin: 7px;
			font-size: 20px;
			color: #f33;
		}

		.action {

			width: 100%;
			padding: 10px 0;
		}


		.action button{

			width: 200px;
			margin: 7px;
			border-radius: 20px;
			height: 40px;
			background: #fff;
			text-align: center;
			border: 1px solid #f33;
			color: #f33;

		}

		.action button:hover{background-color: #f33;color: #fff;}

		.share {

			width: 100%;
			padding-top: 20px;
			padding-bottom: 10px;
		}

		.meeting_link {

			width: 180px;
			padding: 10px;
			border-radius: 10px;
			background: #fff;
			border-width: 1px;
		}
		.fa-copy {
			font-size: 25px;
			margin-left: 2px;
		}

		.social_media {
			text-decoration: none;
			font-size: 30px;
			margin: 7px;
			margin-top: 0;
		}
		.fab-facebook {
			color: #3b5998;
		}
		.fab-twitter {
			color: #00acee;
		}
		.fa-envelope{
			color: #ff6600;
		}
		.room_details {
			text-align: left;
		}
		.fa-link, .fa-share-alt {

			font-size: 20px;
			margin: 7px;
			color: #f33;
		}
		.update_room {
			width: 80%;
			margin-left: 10%;
			background: #f33;
			border: 1px solid #f33;
			padding: 10px;
			text-align: center;
			font-size: 20px;
			color: #fff;
			border-radius: 5px;
		}



	@media only screen and (min-width: 768px) {
		body{
			background-color: #d3d3d3;
		}	

		.box {

			width: 600px;
			margin: 10px;
			border-radius: 7px;
			background: #fff;
			margin-left: 20%;
			border: 1px solid #d3d3d3;
			padding: 20px;
		}


		.room_details {
			width: 600px;
			margin-left: 20%;
			text-align: center;
		}

		.meeting_link {

			width: 450px;
		}

		.meeting_icon {margin-left: 37%;}
		.fa-copy {margin-left: 5px;}


	}
	</style>
</head>
<body>


	<div class="container">
		
			<div class="room_details box">
				

				<div class="meeting_icon"></div>
				<h3 id="room_titleName"></h3>
				<p id="starts_at"><i class="fas fa-clock"></i>  </p>
				<p><i class="fas fa-user"></i>created by dennis</p>

				<div class="action">
					
					<button data-toggle="modal" data-target="#shareModal"><i class="fas fa-edit"></i> Edit</button>
					<button id="open_video"><i class="fas fa-video"></i> Start Now</button>

				</div>

			</div>

			<div class="share_container box text-center">
				

				<div class="share">

					<p><i class="fas fa-link"></i> Meeting link</p>

					<input type="text" disabled="disabled" value="" class="meeting_link" id="meeting_link">
					
					<span id="copy_link" data-placement="top" title="copied to clipboard"><i class="fas fa-copy"></i></span>

					<p style="margin-top: 20px;"><i class="fas fa-share-alt"></i> Share with friends</p>

					
				</div>

				<div class="text-center">
					<a href="" class="social_media" style="margin-left: 30px;" target="_blank" id="fb"><i class="fab fa-facebook"></i></a>
					<a href="" class="social_media" id="mail" target="_blank"><i class="fas fa-envelope"></i></a>
					<a href="" class="social_media" id="twitter" target="_blank"><i class="fab fa-twitter"></i></a>
				</div>

			</div>

	</div>


	<!-- update video Modal -->
	<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Update Room</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        
	      	<div class="update_form">
	      		
	      		<div class="form-group" style="text-align: center;">
				    <label for="room_name1" >Enter room name</label>
				    <input type="text" class="form-control" id="room_name1" placeholder="room name">
				</div>

				<div class="form-group" style="text-align: center;">
				    <label for="start_date" >Start date</label>
				    <input type="date" class="form-control" id="start_date">
				</div>
				<div class="form-group" style="text-align: center;">
				    <label for="start_time" >Start Time</label>
				    <input type="time" class="form-control" id="start_time">
				</div>
				<button class="update_room" id="update_room">Update Room</button>

				<div class="form-group" style="text-align: center;display: none;margin-top: 10px;" id="error_box">
					<div class="alert alert-danger"></div>
				</div>

	      	</div>

	      </div>
	    </div>
	  </div>
	</div>

	<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <script src="access_server.js"></script>

    <script type="text/javascript">
    	
    	var link = location.href
    	var room_id = "<?php echo $_GET['room'];?>"

    	load_share_icon_href()
    	function load_share_icon_href(){


    		$('#fb').attr("href", "https://www.facebook.com/sharer.php?u="+link)
    		$('#twitter').attr("href", "https://twitter.com/share?url="+link+"&amp;text=You have been invited to a meeting at hffworld. click the link to join&amp;hashtags=Hffworld video meeting")
    		$('#mail').attr("href", "mailto:?Subject=Hffworld video meeting &amp;Body=you%have%been%invited%to%a%meeting%on%hffworld.%click%this%link%to%join"+link)
    	}


    	$('#meeting_link').val(link)

    	//getting room deatails

    	getRoomDetails();
    	function getRoomDetails() {

    		var controller = {

					url: 'getRoomDetails.php?room='+room_id,
					method: 'GET',
					callBackFunction: (result) => {

						//console.log(result)

						if(result) {
							


							var room = JSON.parse(result)
							//console.log(room[Object.keys(room)[0]].room_name)

							$('.meeting_icon').html(room[Object.keys(room)[0]].room_name.substring(0,1)[0])
							$('#room_titleName').html(room[Object.keys(room)[0]].room_name)
							$('#room_name1').val(room[Object.keys(room)[0]].room_name)
							//console.log(room[Object.keys(room)[0]].start_time)
							$('#start_time').val(room[Object.keys(room)[0]].start_time)
							$('#start_date').val(room[Object.keys(room)[0]].start_date)
							$('#starts_at').html('<strong>Starts at: </strong>' + new Date(room[Object.keys(room)[0]].start_date +"T"+ room[Object.keys(room)[0]].start_time))


						}else {

							alert('Room does not exist, try again later')
							location.href = 'index.html'
						}

						$('.loader').remove()
						$('#create_room').prop('disabled', 'false')
					},
					error: (err) => {

						console.log(err)
					}
				}

				accessServer(controller)

    	}


    	//editing room

    	$('#update_room').click(()=>{

    		if($('#room_name1').val() == "" || $('#start_time').val() == "" || $('#start_date').val() == "") {

    			$('.alert').html("All fields are required")
    			$('#error_box').css('display', 'block')
    			return

    		}else {
    			//update

    			$('#update_room').prop('disabled', true)
    			$('#update_room').append('<i class="fas fa-spinner fa-spin" style="font-size:20px;color: #f33;"></i>')

    			formData = new FormData()
					formData.append('room_name', $('#room_name1').val())
					formData.append('room_id', room_id)
					formData.append('start_date', $('#start_date').val())
					formData.append('start_time', $('#start_time').val())

				var controller = {

					url: 'saveroomtodb.php?action=update_room',
					data: formData,
					method: 'POST',
					callBackFunction: (result) => {

						//console.log(result)

						if(result.trim() == 'successful') {
							
							location.reload()

						}else {

							$('.alert').html("Could not update room, try again later")
    						$('#error_box').css('display', 'block')
    						$('#update_room').prop('disabled', false)
    						$('.fa-spinner').remove()
						}

					},
					error: (err) => {

						console.log(err)
					}
				}

				accessServer(controller)
    		}
    	})

    	//copying link

    	$('#copy_link').click(()=>{

    		navigator.clipboard.writeText(link).then(() => {
                console.log(link)
                $('#copy_link').tooltip('show')
            })
    	})

    	//getting into room

    	$('#open_video').click(()=>{

    		startVideo()
    	})

    	function startVideo() {
    		

    		//save room_info to local storage
    		localStorage.setItem('room', room_id)

    		location.href = 'video.php'

    	}

    </script>
</body>
</html>