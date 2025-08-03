<?php

session_start();
if (isset($_SESSION['id']) && isset($_SESSION['user_name'])){
	header("Location: already_logged_in.php");
    exit();
}

include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
	$name = $_POST['name'];
	
	$email = $_POST['email'];
    
	$no_phone = $_POST['no_phone'];
    
	$password = $_POST['password'];
    
	$user_type = $_POST['user_type'];
	
	if (empty($name) || empty($email) || empty($no_phone) || empty($password) || empty($user_type)) {
        
		header("Location: register.php?registration_error=Please fill in all the required fields");
        
		exit();
	}
	
if (strtolower($user_type) == 'admin') {
    $sql = "INSERT INTO admin_db (`name`, `email`, `no phone`, `password`, `user type`) VALUES ('$name', '$email', '$no_phone', '$password', '$user_type')";
} elseif (strtolower($user_type) == 'user') {
    $sql = "INSERT INTO users_db (`name`, `email`, `no phone`, `password`, `user type`) VALUES ('$name', '$email', '$no_phone', '$password', '$user_type')";
} else {
    header("Location: register.php?registration_error=Invalid user type");
    exit();
}



	if (mysqli_query($conn, $sql)) {
		
		header("Location: login.php?registration_success=true");
		
		exit();
	
	} else {
		echo "SQL Error: " . mysqli_error($conn);
		
		header("Location: register.php?registration_error=" . urlencode("Database error: " . mysqli_error($conn)));
		
		exit();
	}
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Register</title>

    <link rel="stylesheet" type="text/css" href="style.css">

	<style>

	body {
		background-image: linear-gradient(rgba(0,0,0,0.4),rgba(0,0,0,0.4)),url("tshirt.jpg");
		display: flex;
		justify-content: center;
		align-items: center;
		height: 97vh;
		flex-direction: column;
	}
	
	*{
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		box-sizing: padding-box;
	}

	form {
		width: 300px;
		border: 3px dotted black;
		padding: 30px;
		background: #fff;
		border-radius: 30px;
	}
	
	h2 {
		text-align: center;
		margin-bottom: 0px;
		margin-top: 0px;
		font-size: 26px;
	}

	input {
		display: inline-block;
		border: none;
		width: 95%;
		padding: 10px;
		margin-top: 8px;
		margin-bottom: 5px;
		border-radius: 7px;
		background-color: black;
		color: white;
	}
	
	input::placeholder {
		color: #fff;
	}
	
	label {
		color: black;
		font-size: 14px;
		padding: 3px;
	}
	
	button {
		background: #FFD608;
		padding: 10px 17px;
		color: black;
		border-radius: 5px;
		margin: 13px auto;
		border: none;
		font-size: 17px;
		cursor: pointer;
		width: 40%;
		align-items: center;
		display: block;
	}

	button:hover{
		opacity: 0.9;
	}
	
	#btn {
		background: #FFD608; 
		color: #fff;
		padding: 13px 13px;
		border: none;
		border-radius: 50%;
		cursor: pointer;
		font-size: 16px;
		font-weight: bold;
		position: absolute;
		top: 10px;
		right: 10px;
		width: 30px;
		height: 30px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	#btn:hover {
		background: #C62828;
	}
	
	select {
		display: inline-block;
		border: none;
		width: 100%;
		padding: 10px;
		margin-top: 8px;
		margin-bottom: 5px;
		border-radius: 7px;
		background: black;
	}
	
	.error {   
		font-size: 13px;
		margin-top: 0px;
		margin-bottom: 0px;
	}
	
	.usertype{
		color: #fff;
	}
	
	</style>

</head>

<body>

	<button onclick="location.href='login.php'" id= "btn">X</button>

	<form action="register.php" method="post">

        <h2>REGISTER</h2>

        <?php if (isset($_GET['registration_error'])) { ?>

            <p class="error"><?php echo $_GET['registration_error']; ?></p>

        <?php } ?>

        <label>Name</label>
        <input type="text" name="name" placeholder="Name"><br>
		<br>
		<label>Number phone</label>
        <input type="text" name="no_phone" placeholder="Number Phone"><br>
		<br>
        <label>Email</label>
        <input type="email" name="email" placeholder="Email"><br>
		<br>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password"><br>
        <br>
		<label>User Type</label>
		<select name="user_type" style="background-color: black; color: white;">
			<option value="user" style="background-color: black; color: white;">User</option>
			<option value="admin" style="background-color: black; color: white;">Admin</option>
		</select>
		<br>
        <button type="submit">Register</button>
	</form>
	
</body>

</html>