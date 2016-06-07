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
          if ($stmt->rowCount) {
          	$result = "email registered";
          }
          else {
			  $sql = 'SELECT * FROM users WHERE  username = :username';
	          $stmt = $conn->prepare($sql);
	          $stmt->bindParam(':username', $username, PDO::PARAM_STR);
	          $stmt->execute();
	          if($stmt->rowCount())
	          {
				      $result="user already exists";	
	          }  
	          elseif(!$stmt->rowCount())
	          {
				  	  $sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`, `activation_code`, `activated`) VALUES (NULL, :username, :useremail, :password, :activcode, :activated)";
	          		  $stmt = $conn->prepare($sql);
	          		  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
	          		  $stmt->bindParam(':useremail', $email, PDO::PARAM_STR);
	          		  $stmt->bindParam(':password', md5($password), PDO::PARAM_STR);
	          		  $code = md5(uniqid(rand()));
	          		  $stmt->bindParam(':activcode', $code, PDO::PARAM_STR);
	          		  $stmt->bindParam(':activated', $a = 0, PDO::PARAM_STR);
	          		  $stmt->execute();

	          		  $message = "     
				      Hello $username,
				      <br /><br />
				      Welcome to PayApp!<br/>
				      To complete your registration  please , just click following link<br/>
				      <br /><br />
				      <a href='http://www.SITE_URL.com/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
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
					  $mail->Username="rohit19081995@gmail.com";  
					  $mail->Password="r4o3h2i1t@googlemail";            
					  $mail->SetFrom('rohit19081995@gmail.com','PayApp Account Verification');
					  $mail->AddReplyTo("rohit19081995@gmail.com","PayApp Customer Service");
					  $mail->Subject    = $subject;
					  $mail->MsgHTML($message);
					  $mail->Send();

	          		  $result="user added";
	          }
	      }
		  
		  // send result back to android
   		  echo $result;
  	}
	
?>
