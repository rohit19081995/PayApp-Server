<?php

	$db_name = "webservice";
	$username = "wayne";
	$password = "r4o3h2i1t@php";
	$server_name = "localhost";

	$conn = mysqli_connect($server_name, $username, $password, $db_name);

	if($conn) {
		echo "connection successful";
	}
	else {
		echo "connection unsuccessful";
	}

?>
