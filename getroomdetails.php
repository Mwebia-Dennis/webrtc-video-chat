<?php

	require 'connection.php';

	$data = [];

	$sql = "SELECT * FROM video_rooms where `room_id` = '".$_GET['room']."' order by date_added desc";

	//echo $sql;
	$result = $conn->query($sql);

	if($result->num_rows > 0) {

		while($row = $result->fetch_assoc()) {

			$data[] = $row;
		}
	}

	echo (!empty($data))?json_encode($data):'';
?>