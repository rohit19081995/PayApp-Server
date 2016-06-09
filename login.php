<?php

	require "conn.php";
	$user_name = "Rohit";
	$user_pass = "wayne";
	$mysql_query = "select * from users where username like '$username' and password '$user_pass'";

	#$result = mysqli_query($conn, $mysql_query);
	if (mysqli_num_rows(mysqli_query($conn, $mysql_query)) > 0) {
		echo "login success";
	}

?>