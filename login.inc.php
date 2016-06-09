<?php

	 include 'config.inc.php';
	 
	 // Check whether username or password is set from android	
     if(isset($_POST['username']) && isset($_POST['password']))
     {
		  // Innitialize Variable
		      $result='';
	   	    $username = $_POST['username'];
          $password = md5($_POST['password']);
		  
		  // Query database for row exist or not
          $sql = 'SELECT * FROM users WHERE  username = :username AND password = :password';
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':username', $username, PDO::PARAM_STR);
          $stmt->bindParam(':password', $password, PDO::PARAM_STR);
          $stmt->execute();
          if($stmt->rowCount())
          {
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if($res['activated'] == 'Y') {
              $result="true";
            }
            else {
              $result = "verify";
            }
			      	
          }  
          elseif(!$stmt->rowCount())
          {
			  	  $result="false";
          }
            
		  
		  // send result back to android
   		  echo $result;
  	}
	
?>