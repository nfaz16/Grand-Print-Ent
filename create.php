<?php 

include "config.php";

  if (isset($_POST['submit'])) {

    $name = $_POST['name'];
	
	$gender = $_POST['gender'];
	
	$date_of_birth = $_POST['date_of_birth'];
	
	$email = $_POST['email'];
	
	$no_phone = $_POST['no_phone'];

    $sql = "INSERT INTO `users_db`(`name`, `gender`, `date of birth`, `email`, `no phone`) VALUES ('$name','$gender','$date_of_birth', '$email','$no_phone')";

    $result = $conn->query($sql);
	
	if (!ctype_digit($no_phone)) {
        
		header("Location: register.php?registration_error=Invalid phone number");
        
		exit();
    }

    if ($result == TRUE) {

      echo "New record created successfully.";
	  header('Location: userhome.php');
	  exit();

    }else{

      echo "Error:". $sql . "<br>". $conn->error;

    } 

    $conn->close(); 

  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    
	<meta charset="UTF-8">
    
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<link rel="stylesheet" type="text/css" href="style.css">
	
	<title>Create</title>
	
	<style>
        body {
			background: linear-gradient(rgba(0,0,0,0.4),rgba(0,0,0,0.4)),url("hb.jpg");
			display: flex;
			justify-content: center;
			align-items: center;
			height: 97vh;
			flex-direction: column;
			font-family: cursive;
		}	

        form {
			width: 300px;
			border: 3px dotted white;
			padding: 32px;
			background: #E312A4;
			border-radius: 30px;
		}
		
		h2 {
			color: white;
			margin-bottom: 8px;
			margin-top: 3px;
		}

        fieldset {
			border: 2px solid #FFD608;
			padding: 20px;
			margin-bottom: 10px;
        }

        legend {
			font-weight: bold;
			font-size: 20px;
		}

        input[type="text"],
        input[type="email"],
        input[type="date"]
		{
            width: 95%;
			padding: 9px;
			margin-top: 7px;
			box-sizing: border-box;
			border: none;
			border-radius: 7px;
			font-family: cursive;
		}
		
		input[type="radio"] {
			display: inline-block;
			width: 15px;
			height: 15px;
			cursor: pointer;
			margin-left: 20px;
		}

        input[type="submit"] {
			background: #FFD608;
			padding: 15px 30px;
			color: black;
			border-radius: 5px;
			border: none;
			font-size: 17px;
			font-family: cursive;
			cursor: pointer;
			width: 40%;
			margin-left: 78px;
		}

        input[type="submit"]:hover {
			opacity: 0.9;
		}
		
    </style>
	
</head>
	
<body>
	<h2>User Create Form</h2>
	
	<form action="create.php" method="POST">

  <fieldset>

    <legend>Buddy information:</legend>

    <label for="name">Name:</label><br>

    <input type="text" name="name" placeholder="Name">

    <br>

    <label for="gender">Gender:</label><br>

    <input type="radio" name="gender" value="Male">Male

    <input type="radio" name="gender" value="Female">Female

    <br>
	
	<label for="date_of_birth">Date of Birth:</label><br>

    <input type="date" name="date_of_birth">

    <br>

    <label for="email">Email:</label><br>

    <input type="email" name="email" placeholder="Email">

    <br>

    <label for="no_phone">Phone Number:</label><br>

    <input type="text" name="no_phone" placeholder="Number Phone">

    <br><br>

    <input type="submit" name="submit" value="Add">

  </fieldset>

</form>

</body>

</html>