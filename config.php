<?php

$sname = "localhost";

$username = "root"; 

$password = ""; 

$dbname = "grandprint"; 

$conn = new mysqli($sname, $username, $password, $dbname);

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}

?> 