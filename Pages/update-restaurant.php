<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Get restaurant details
$rest_id = $_GET['restaurant_id'];
$query = "SELECT * FROM restaurant WHERE restaurant_id='$rest_id'";
$result = $conn->query($query);
if(!$result) die("Error retrieving restaurant data: ".$conn->error);

$restaurant = $result->fetch_array(MYSQLI_ASSOC);
$name = $restaurant['name'];

echo <<<_END
<head>
	<title>DineScout | Update $name</title>
</head>
_END;

// Update restaurant details in the database once form is submitted
if(isset($_POST['update'])){
	$rest_id = $_POST['restaurant_id'];
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
	
	$query = "UPDATE restaurant SET name='$name', location='$location', website='$website', phone_number='$phone', email='$email', operating_hours='$hours', description='$description' WHERE restaurant_id=$rest_id";
	
	$result = $conn->query($query);
	if(!$result) die("Database connection failed: ".$conn->connect_error);
	
	// Redirect to the new restaurant's view page
	header("Location: view-restaurant.php?restaurant_id=".$rest_id);
}

// Close connection to the database
$conn->close();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/update-restaurant.css">
	<link rel="icon" type="image/x-icon" href="../Images/ds_logo_favicon.png">
</head>
<body>
	<!-- Site Header -->
    <header class="top-bar">
        <div class="left-text">
            <a href="home.php">D | S</a>
        </div>
        <div class="right-button">
            <a href="user-account.php" class="no-underline">User Account</a>
        </div>
    </header>
    
	<!-- Site Main Content -->
    <div class="wrapper">
        <div class="new-item-container">
            <div class="logo-container">
				<img src="../Images/ds_logo_simple_transparent.png" alt="DineScout Logo" class="center-logo">
			</div>

            <h1>Update <?php echo htmlspecialchars($restaurant['name']) ?? 'Restaurant Not Found' ?></h1>
            <br>
            <form action="update-restaurant.php?restaurant_id=<?php echo htmlspecialchars($restaurant['restaurant_id'] ?? "0"); ?>" method=POST class="input-box">
                <div class="top-buttons">
                    <a href="view-restaurant.php?restaurant_id=<?php echo htmlspecialchars($restaurant['restaurant_id']); ?>" class="cancel-button">Cancel</a>
					<button type=button class="delete-button" onclick="openDeletePopup()">Delete</button>
                    <button type=submit class="create-button">Update</button>
                </div>
                <div class="input-group">
                    <label for="rest-name">Name:</label>
                    <input type="text" id="rest-name" name="rest-name" value="<?php echo htmlspecialchars($restaurant['name'] ?? "");?>" required>
                </div>
                <div class="input-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($restaurant['location'] ?? "");?>" required>
                </div>
                <div class="input-group">
                    <label for="website">Website:</label>
                    <input type="text" id="website" name="website" value="<?php echo htmlspecialchars($restaurant['website'] ?? "");?>" required>
                </div>
                <div class="input-group">
                    <label for="contact-phone">Contact Phone:</label>
                    <input type="text" id="contact-phone" name="contact-phone" value="<?php echo htmlspecialchars($restaurant['phone_number'] ?? "");?>">
                </div>
                <div class="input-group">
                    <label for="contact-email">Contact Email:</label>
                    <input type="text" id="contact-email" name="contact-email" value="<?php echo htmlspecialchars($restaurant['email'] ?? "");?>">
                </div>
				<div class="input-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($restaurant['description'] ?? "");?></textarea>
                </div>
				<div class="input-group">
                    <label for="hours">Operating Hours:</label>
                    <textarea id="hours" name="hours"><?php echo htmlspecialchars($restaurant['operating_hours'] ?? "");?></textarea>
                </div>
				<input type='hidden' name='update' value='yes'>
				<input type='hidden' name='restaurant_id' value="<?php echo htmlspecialchars($restaurant['restaurant_id'] ?? "0");?>">
            </form>
			<br>
			<br>
        </div>
    </div>
    
    <footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
	
	<div id="deletePopup" class="deletePopup">
        <div class="deletePopup-content">
            <div class="deletePopup-header">Warning</div>
            <p>This action will remove this restaurant. Do you wish to continue?</p>
            <div class="deletePopup-buttons">
                <button class="deletePopup-button cancel-button-deletePopup" onclick="closeDeletePopup()">Cancel</button>
                <button class="deletePopup-button continue-button" onclick="redirectToDelete()">Continue</button>
            </div>
        </div>
    </div>

    <script>
        function openDeletePopup() {
            document.getElementById('deletePopup').style.display = 'flex';
			
        }
		
		function redirectToDelete() {
			window.location.href = 'delete-restaurant.php?restaurant_id=<?php echo htmlspecialchars($restaurant['restaurant_id']); ?>';
		}

        function closeDeletePopup() {
            document.getElementById('deletePopup').style.display = 'none';
        }
    </script>
</body>
</html>