<?php

	$db_name = 'test';
	$db_pass = '';
	$server_name = 'localhost';
	$db_user_name = 'root';

	$conn = new Mysqli($server_name, $db_user_name,$db_pass, $db_name);

	if($conn->connect_error){

		die("Could not connect to database");
	}

?>