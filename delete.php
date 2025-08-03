<?php

include "config.php";

if (isset($_GET['id'])) {

    $user_id = $_GET['id'];

    $sql = "DELETE FROM `users_db` WHERE `id`='$user_id'";
    $result = $conn->query($sql);

    echo '<html>
            <head>
                <title>Delete</title>
                <style>
                    body {
                        font-family: "Arial", sans-serif;
                        background-color: #f0f0f0;
                        text-align: center;
                        padding: 20px;
                    }
                    
                    .message {
                        background-color: #dff0d8;
                        color: #3c763d;
                        border: 1px solid #d6e9c6;
                        padding: 15px;
                        margin: 20px auto;
                        width: 50%;
                        border-radius: 5px;
                    }
                    
                    .error {
                        background-color: #f2dede;
                        color: #a94442;
                        border: 1px solid #ebccd1;
                        padding: 15px;
                        margin: 20px auto;
                        width: 50%;
                        border-radius: 5px;
                    }
					
					a.back-link {
						float: right;
						background: #FFD608;
						padding: 15px 25px;
						color: black;
						border-radius: 5px;
						margin-right: 5px;
						border: none;
						font-size: 17px;
						position: absolute;
						top: 25px;
						right: 25px;
					}

					a.back-link:hover {
						opacity: 0.9;
					}
                </style>
            </head>
            <body>';

    if ($result == TRUE) {
        echo '<div class="message">Record deleted successfully.</div>';
    } else {
        echo '<div class="error">Error: ' . $conn->error . '</div>';
    }
	
	echo'<a class="back-link" href="userhome.php">Back</a>';

    echo '</body>
        </html>';
}

?>
