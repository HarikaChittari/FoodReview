<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout | Add A Restaurant</title>
    <link rel="stylesheet" href="../CSS/add-restaurant.css">
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
    
    <div class="wrapper">
        <div class="new-item-container">
            <div class="logo-container">
				<img src="../Images/ds_logo_simple_transparent.png" alt="DineScout Logo" class="center-logo">
			</div>

            <h1>Add A Restaurant</h1>
            <br>
            <form action="add-restaurant.php" method=POST class="input-box">
                <div class="top-buttons">
                    <button type=button class="cancel-button" onclick="window.location.href = 'home.php';">Cancel</button>
                    <button type=submit class="create-button">Create</button>
                </div>
                <div class="input-group">
                    <label for="rest-name">Name:</label>
                    <input type="text" id="rest-name" name="rest-name" required>
                </div>
                <div class="input-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" required>
                </div>
                <div class="input-group">
                    <label for="website">Website:</label>
                    <input type="text" id="website" name="website" required>
                </div>
                <div class="input-group">
                    <label for="contact-phone">Contact Phone:</label>
                    <input type="text" id="contact-phone" name="contact-phone">
                </div>
                <div class="input-group">
                    <label for="contact-email">Contact Email:</label>
                    <input type="text" id="contact-email" name="contact-email">
                </div>
				<div class="input-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"></textarea>
                </div>
				<div class="input-group">
                    <label for="hours">Operating Hours:</label>
                    <textarea id="hours" name="hours"></textarea>
                </div>
            </form>
			<br>
			<br>
        </div>
    </div>
    
    <footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
</body>
</html>

<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Enter restaurant details in the database
if(isset($_POST['rest-name'])){
	$name = $_POST['rest-name'];
	$location = $_POST['location'];
	$website = $_POST['website'];
	if(isset($_POST['contact-phone'])) {
		$phone = $_POST['contact-phone'];
	}else{
		$phone = NULL;
	}
	if(isset($_POST['contact-email'])) {
		$email = $_POST['contact-email'];
	}else{
		$email = NULL;
	}
	if(isset($_POST['description'])) {
		$description = $_POST['description'];
	}else{
		$description = NULL;
	}
	if(isset($_POST['hours'])) {
		$hours = $_POST['hours'];
	}else{
		$hours = NULL;
	}
	
	$query = "INSERT INTO restaurant (name, location, website, phone_number, email, operating_hours, description) VALUES('$name', '$location', '$website', '$phone', '$email', '$hours', '$description')";
	
	$result = $conn->query($query);
	if(!$result) die("Database connection failed: ".$conn->connect_error);
	
	// Redirect to the new restaurant's view page
	$last_id = $conn->insert_id;
	header("Location: view-restaurant.php?restaurant_id=".$last_id);
}

// Close connection to the database
$conn->close();

?>