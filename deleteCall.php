<?php


	require 'connection.php';
	$callNo = file_get_contents('php://input');
	if (is_numeric($callNo)) {

	  if($conn->query("DELETE FROM calls WHERE callNo=$callNo")){

	  	echo "true";
	  }else {

	  	echo "false";
	  }

	}
?>

