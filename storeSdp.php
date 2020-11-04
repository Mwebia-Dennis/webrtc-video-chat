<?php
	require 'connection.php';

	 $result = $conn->query("INSERT INTO `calls` (`id`, `room_id`, `Sdp`, `sender`, `receiver`) VALUES (NULL, '".$conn->real_escape_string($_POST['room_id'])."', '".$conn->real_escape_string($_POST['Sdp'])."', '".$conn->real_escape_string($_POST['sender'])."', '".$conn->real_escape_string($_POST['receiver'])."')");

	  echo ($result)?'successful':"failed";
?>