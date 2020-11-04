<?php
	

	require 'connection.php';

	switch ($_GET['action']) {
		case 'set_participant':
			
			echo ($conn->query("INSERT INTO `room_participants` (`id`, `user_id`, `room_id`) VALUES ('', '".$conn->real_escape_string($_POST['user_id'])."', '".$conn->real_escape_string($_POST['room_id'])."')"))?'successful':'failed';

			break;
		case 'get_participant':
			
			$result = $conn->query("SELECT user_id FROM `room_participants` where room_id = '".$conn->real_escape_string($_GET['room_id'])."' and user_id != ".$conn->real_escape_string($_GET['user_id']));

			if($result->num_rows > 0) {

				echo json_encode($result->fetch_assoc());
			}
			break;
		
		default:
			# code...
			break;
	}
?>