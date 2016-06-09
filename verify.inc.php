<?php

	include 'config.inc.php';

	$id = base64_decode($_GET['id']);
	$code = $_GET['code'];
	echo $id;

	$sql = 'SELECT * FROM users WHERE  id = :idofuser AND activation_code = :authentication_code';
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':idofuser', $id, PDO::PARAM_INT);
	$stmt->bindParam(':authentication_code', $code, PDO::PARAM_STR);
	$stmt->execute();
	$result = ($stmt->fetch(PDO::FETCH_ASSOC));
	$rows = $stmt->rowCount();
	if($rows == 1) {
		echo "hi";
		if($result['activated'] == 'N') {
			?> Welcome to PayAPp
			<?php
			$sql = 'UPDATE `users` SET `activated` = \'Y\' WHERE `users`.`id` = :idofuser';
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':idofuser', $id, PDO::PARAM_INT);
			$stmt->execute();
			echo "hello";
		}
		else {
			?>You have already been verified
			<?php
		}

	}
	else if($rows > 1) {
		?>Invalid code
		<?php

	}
	else {
		?> Please sign up
		<?php
		
	}
?>