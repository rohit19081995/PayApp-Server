<?php

	 include 'config.inc.php';
	 
	 // Check whether username or password is set from android	
     if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']))
     {
		  // Innitialize Variable
		      $result='';
	   	    $username = $_POST['username'];
          $password = $_POST['password'];
          $email = $_POST['email'];
		  
		  // Query database for row exist or not
		  $sql = 'SELECT * FROM users WHERE  email = :user_email';
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':user_email', $email, PDO::PARAM_STR);
          $stmt->execute();
          if ($conn->query("SELECT FOUND_ROWS()")->fetchColumn() > 0) {
          	$result = "email registered";
          }
          else {
			  $sql = 'SELECT * FROM users WHERE  username = :username';
	          $stmt = $conn->prepare($sql);
	          $stmt->bindParam(':username', $username, PDO::PARAM_STR);
	          $stmt->execute();
	          if($stmt->rowCount() >0)
	          {
				      $result="user already exists";	
	          }  
	          else
	          {		  $code = md5(uniqid(rand()));
				  	  $sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`, `activation_code`, `activated`) VALUES (NULL, :username, :useremail, :password, :activcode, :activated)";
	          		  $stmt = $conn->prepare($sql);
	          		  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
	          		  $stmt->bindParam(':useremail', $email, PDO::PARAM_STR);
	          		  $stmt->bindParam(':password', md5($password), PDO::PARAM_STR);
	          		  $stmt->bindParam(':activcode', $code, PDO::PARAM_STR);
	          		  $stmt->bindParam(':activated', $a = 'N', PDO::PARAM_STR);
	          		  $stmt->execute();
	          		  $id = $conn->lastInsertId();
	          		  $baseid = base64_encode($id);
	          		  $message = "     
				      Hello $username,
				      <br /><br />
				      Welcome to PayApp!<br/>
				      To complete your registration, just click following link<br/>
				      <br /><br />
				      <a href='http://192.168.1.5/webservice/verify.inc.php?id=$baseid&code=$code'>CLICK HERE TO ACTIVATE YOUR ACCOUNT</a>
				      <br /><br />
				      Thanks,";

				      $subject = "Confirm registration";

	          		  require_once('PHPMailer/PHPMailerAutoload.php');
					  $mail = new PHPMailer();
					  $mail->IsSMTP(); 
					  $mail->SMTPDebug  = 0;                     
					  $mail->SMTPAuth   = true;                  
					  $mail->SMTPSecure = "ssl";                 
					  $mail->Host       = "smtp.gmail.com";      
					  $mail->Port       = 465;             
					  $mail->AddAddress($email);
					  $mail->Username="csmdstudios.payapp@gmail.com";  
					  $mail->Password="PcasymAdppstudios";            
					  $mail->SetFrom('rohit19081995@gmail.com','PayApp Account Verification');
					  $mail->AddReplyTo("rohit19081995@gmail.com","PayApp Customer Service");
					  $mail->Subject    = $subject;
					  $mail->MsgHTML($message);
					  if($mail->Send()) {
					  	$result="user added";
					  }
					  else {
					  	$result="mail failed";
					  	$sql = "DELETE FROM `users` WHERE `users`.`id` = :id";
					  	$stmt = $conn->prepare($sql);
					  	$stmt->bindParam(':id', $id, PDO::PARAM_STR);
					  	$stmt->execute();

					  }
	          }
	      }
		  
		  // send result back to android
   		  echo $result;
  	}
	
?>
