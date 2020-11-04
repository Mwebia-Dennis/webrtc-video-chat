<?php
	require 'connection.php';

	$data = json_decode(file_get_contents('php://input'), true);

	var_dump($data);

	$sql = "INSERT INTO `ice_candidates` (`id`, `room_id`, `sender`, `ice_candidate`) VALUES (NULL, '".$conn->real_escape_string($data['room_id'])."', '".$conn->real_escape_string($data['sender'])."', '".$conn->real_escape_string($data['ice'])."')";

	$result = $conn->query($sql);

	echo (($result)?'success':'failed');
	

?>