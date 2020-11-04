<?php

	require 'connection.php';

	$data = [];

	$sql = "SELECT ice_candidate FROM `webrtc_data` where room_id = '".$conn->real_escape_string($_GET['room_id'])."' and sender != '".$conn->real_escape_string($_GET['user_id'])."';";

	$result = $conn->query($sql);

	if($result->num_rows > 0) {

		while ($row = $result->fetch_assoc()) {

			$data[] = $row;

		}

		echo json_encode($data);
	}


?>