<?php
	
	$host = "localhost";
	
	$user = "root";
	
	$password = "";
	
	$db = "grandprint";

session_start();

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("connection error". mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
	$name = $_POST["username"];
    
	$password = $_POST["password"];

    if (empty($name) || empty($password)) {
        
		header("Location: login.php?error=name and password are required");
        
		exit();
    }
	
	$admin_sql = "SELECT * FROM admin_db WHERE name=? AND password=?";
    $admin_stmt = mysqli_prepare($data, $admin_sql);

    if ($admin_stmt === false) {
        die("Error preparing admin statement: " . mysqli_error($data));
    }

    mysqli_stmt_bind_param($admin_stmt, "ss", $name, $password);

    mysqli_stmt_execute($admin_stmt);

    $admin_result = mysqli_stmt_get_result($admin_stmt);

    $user_sql = "SELECT * FROM users_db WHERE name=? AND password=?";
    $user_stmt = mysqli_prepare($data, $user_sql);

    if ($user_stmt === false) {
        die("Error preparing user statement: " . mysqli_error($data));
    }

    mysqli_stmt_bind_param($user_stmt, "ss", $name, $password);

    mysqli_stmt_execute($user_stmt);

    $user_result = mysqli_stmt_get_result($user_stmt);
	
	if ($admin_row = mysqli_fetch_assoc($admin_result)) {
        $_SESSION["name"] = $name;

        if ($admin_row["user type"] == "admin") {
            header("location: adminhome.php");
            exit();
        }
    }
    elseif ($user_row = mysqli_fetch_assoc($user_result)) {
        $_SESSION["name"] = $name;

        if ($user_row["user type"] == "user") {
            header("location: userhome.php");
            exit();
        }
	} else {
        
		header("Location: login.php?error=Incorect name or password");

		exit();
    
	}

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<style>
		body {
			background-image: linear-gradient(rgba(0,0,0,0.4),rgba(0,0,0,0.4)),url("tshirt.jpg");
			display: flex;
			justify-content: center;
			align-items: center;
			height: 80vh;
			flex-direction: column;
		}
		
		*{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			box-sizing: padding-box;
		}
		
		h1 {
			text-align: center;
			margin-bottom: 0px;
			margin-top: 0px;
			font-size: 27px;
		}
		
		form {
			width: 300px;
			border: 3px dotted black;
			padding: 32px;
			background: #fff;
			border-radius: 30px;
			text-align: left;
		}
		
		label {
			color: black;
			font-size: 14px;
			padding: 3px;
		}
		
		input {
			display: inline-block;
			border: none;
			width: 95%;
			padding: 8px;
			margin-top: 8px;
			margin-bottom: 5px;
			border-radius: 7px;
			background-color: black;
			color: white;
		}
		
		input::placeholder {
		  color: #fff;
		}
		
		button {
			background: #FFD608;
			padding: 10px 17px;
			color: black;
			border-radius: 5px;
			margin-right: 10px;
			margin-left: 90px;
			margin-top: 13px;
			border: none;
			font-size: 17px;
			cursor: pointer;
			width: 40%;
			background-color: #F5CF27;
			transition: background-color 0.3s, transform 0.3s;
		}

		button:hover{
			background-color: #F5CF27;
		    transform: scale(1.05);
			color: black;
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
		
		.error {
			font-size: 15px;
			color: red;
			text-align: center;
		}
	</style>
</head>

<body>
	<button onclick="location.href='home.php'" id="btn">X</button>
    
	<center><br><br><br><br>
		<div>
			<br><br>
			<form action="" method="POST">
				<h1>LOGIN</h1><br>
		
			<?php if (isset($_GET['error'])) { ?>
				<p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
			<?php } ?>
				
				<div>
					<label class="highlight-label">Username</label>
					<input type="text" name="username" placeholder="User Name" required>
				</div><br><br>
				<div>
					<label class="highlight-label">Password</label>
					<input type="password" name="password" placeholder="Password" required>
				</div><br>
                
				<div>
					<button type="submit">Login</button>
				</div><br>
				
				<p>Don't have an account? <a href="register.php">Register here</a>.</p>
			</form><br><br>
		</div>
	</center>
</body>
</html>