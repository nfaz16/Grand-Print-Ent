<?php 

include "config.php";

    if (isset($_POST['update'])) {
		print_r($_POST);

        $user_id = $_POST['user_id'];

        $name = $_POST['name'];
		
		$email = $_POST['email'];

        $no_phone = $_POST['no_phone'];

        $sql = "UPDATE `users_db` SET `name`='$name', `email`='$email',`no phone`='$no_phone' WHERE `id`='$user_id'"; 

        $result = $conn->query($sql); 

        if ($result) {

            echo "Record updated successfully.";
			header('Location: userhome.php');
			exit();

        }else{

            echo "Error:" . $sql . "<br>" . $conn->error;

        }

    } 

if (isset($_GET['id'])) {

    $user_id = $_GET['id']; 

    $sql = "SELECT * FROM `users_db` WHERE `id`='$user_id'";

    $result = $conn->query($sql); 

    if ($result->num_rows > 0) {        

        while ($row = $result->fetch_assoc()) {

            $name = $row['name'];
			
			$email = $row['email'];
			
			$no_phone = $row['no phone'];

            $id = $row['id'];

        } 

    ?>




<!DOCTYPE html>

<html>

<head>
	
	<title>Update</title>
	
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
		padding: 15px 20px;
		color: black;
		border-radius: 4px;
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

	<h2>Edit User Information</h2>

	<form action="" method="post">

		<fieldset>

		<legend>User information:</legend>

		<label for="name">Name:</label><br>
		
		<input type="text" name="name" placeholder="Name" value="<?php echo $name; ?>">
        
		<input type="hidden" name="user_id" value="<?php echo $id; ?>">

		<br>

		<label for="email">Email:</label><br>

		<input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>">


		<br>

		<label for="no_phone">Phone Number:</label><br>

		<input type="text" name="no_phone" placeholder="Number Phone"  value="<?php echo $no_phone; ?>">

		<br><br>
	
		<input type="submit" value="Update" name="update">

	  </fieldset>

	</form> 

</body>

</html>  
 <?php

    } else{ 

        header('Location: update.php');

    } 

}

?>