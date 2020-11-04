	<?php
	
	require 'connection.php';

	switch ($_GET['action']) {
		case 'set_room':
			$room_id = md5(uniqid());

			$sql = "";

			if($_POST['start_date'] !="" && $_POST['start_time'] !=""){

				$sql = "INSERT INTO `video_rooms` (`id`, `room_id`, `room_name`, `added_by` , `start_date`, `start_time`,`date_added`) VALUES (NULL, '".$room_id."', '".$conn->real_escape_string($_POST['room_name'])."', '".$conn->real_escape_string($_POST['user_id'])."', '".$conn->real_escape_string($_POST['start_date'])."', '".$conn->real_escape_string($_POST['start_time'])."',current_timestamp())";

			}else {

				$sql = "INSERT INTO `video_rooms` (`id`, `room_id`, `room_name`, `added_by` ,`date_added`) VALUES (NULL, '".$room_id."', '".$conn->real_escape_string($_POST['room_name'])."', '".$conn->real_escape_string($_POST['user_id'])."',current_timestamp())";
			}

			$result = $conn->query($sql);


			echo ($result)?$room_id:"failed";
			break;

		case 'update_room':
			
			$sql = "UPDATE `video_rooms` SET `room_name` = '".$conn->real_escape_string($_POST['room_name'])."', `start_date` = '".$conn->real_escape_string($_POST['start_date'])."', `start_time` = '".$conn->real_escape_string($_POST['start_time'])."' WHERE `room_id` = '".$conn->real_escape_string($_POST['room_id'])."'";

			$result = $conn->query($sql);


			echo ($result)?"successful":"failed";

			break;
		
		default:
			# code...
			break;
	}

?>