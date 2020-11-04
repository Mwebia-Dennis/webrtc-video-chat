<?php
require 'connection.php';
$room_id = $_GET["room_id"];
$user_id = $_GET["user_id"];
//echo $callNo;

$sql = "SELECT Sdp, sender, receiver FROM calls WHERE room_id='".$conn->real_escape_string($room_id)."' and receiver ='".$conn->real_escape_string($user_id)."'";

$result = $conn->query($sql);

  if($result->num_rows > 0){

  		$data = [];

  		while ($row = $result->fetch_assoc()) {
  		
  			$data[] = $row;

  		}

    	echo json_encode($data);
    }
?>