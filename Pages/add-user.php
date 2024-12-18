<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Enter membership information in the database
if(isset($_POST['username'])) {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$fname = $_POST['firstName'];
	$lname = $_POST['lastName'];
	$pword = password_hash($_POST['tempPW'], PASSWORD_DEFAULT);
	
	$query = "INSERT INTO user (username, email, first_name, last_name, password) VALUES('$username', '$email', '$fname', '$lname', '$pword')";
	
	$result = $conn->query($query);
	if(!$result) die("Failed to create new member: ".$conn->connect_error);
	
	// Redirect to the new user's view page
	$last_id = $conn->insert_id;
	header("Location: view-user.php?user_id=".$last_id);
}

// Close connection to the database
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout | Create User</title>
    <link rel="stylesheet" href="../CSS/add-user.css">
	<link rel="icon" type="image/x-icon" href="../Images/ds_logo_favicon.png">
</head>

<body>
	<header class="top-bar">
        <div class="left-text">
            <a href="home.php">D | S</a>
        </div>
        <div class="right-button">
            <a href="user-account.php" class="no-underline">User Account</a>
        </div>
    </header>
	
	<div class="center-logo">
        <img src="../Images/ds_logo_simple_transparent.png" alt="DineScout Logo">
		<h1>Create New User</h1>
    </div>
	
	<form action="add-user.php" method=POST class="add-user-container">
		<div class="top-buttons">
			<button type=button class="cancel-button" onclick="window.location.href = 'view-all-users.php' ">Cancel</button>
			<button type=submit class="create-user-button">Create</a>
		</div>
		
		<div class="add-user">
			<div class="user-input-group">
				<label for="username">Username:</label>
				<input type="text" name="username" required></input>
			</div>
			<div class="user-input-group">
				<label for="email">Email:</label>
				<input type="text" name="email" required></input>
			</div>
			<div class="user-input-group">
				<label for="firstName">First Name:</label>
				<input type="text" name="firstName" required></input>
			</div>
			<div class="user-input-group">
				<label for="lastName">Last Name:</label>
				<input type="text" name="lastName" required></input>
			</div>
			<div class="user-input-group">
				<label for="tempPW">Temporary Password:</label>
				<input type="text" name="tempPW" required></input>
			</div>
		</div>
	</form>
	
	<footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>

</body>

</html>