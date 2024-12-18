<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Retrieve user information
$user_id = $_GET['user_id'];
$query = "SELECT * FROM user WHERE user_id = $user_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving user data: ".$conn->error);
$user = $result->fetch_array(MYSQLI_ASSOC);
$username = htmlspecialchars($user['username']);

echo <<<_END
<head>
	<title>DineScout | View $username</title>
</head>
_END;

// Close connection to the database
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/view-user.css">
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
		<h1>View User</h1>
    </div>
	
	<div class="user-container">
		<div class="top-buttons">
			<a href="view-all-users.php" class="back-button">Back</a>
			<button type=button class="delete-user-button" onclick="openDeletePopup()">Delete</button>
			<a href="update-user.php?user_id=<?php echo $user_id ?>" class="update-user-button">Update</a>
		</div>
		
		<div class="top-buttons">
			<a href="view-user-reviews.php?user_id=<?php echo $user_id ?>" class="view-reviews-button">View Reviews</a>
			<a href="view-user-follows.php?user_id=<?php echo $user_id ?>" class="view-follows-button">View Follows</a>
		</div>

		<div class="user-info">
			<div class="user-info-item">
				<label for="username">Username: </label>
				<p id="username"><?php echo htmlspecialchars($user['username']); ?></p>
			</div>
			<div class="user-info-item">
				<label for="name">Name: </label>
				<p id="name"><?php echo htmlspecialchars($user['first_name']); ?> <?php echo htmlspecialchars($user['last_name']); ?></p>
			</div>
			<div class="user-info-item">
				<label for="email">Email: </label>
				<p id="email"><?php echo htmlspecialchars($user['email']); ?></p>
			</div>
		</div>
	</div>

    <footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
	
	<!-- Delete Popup -->
	<div id="deletePopup" class="deletePopup">
        <div class="deletePopup-content">
            <div class="deletePopup-header">Warning</div>
            <p>This action will remove this user's profile. Do you wish to continue?</p>
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
			window.location.href = 'delete-user.php?user_id=<?php echo htmlspecialchars($user['user_id']); ?>';
		}

        function closeDeletePopup() {
            document.getElementById('deletePopup').style.display = 'none';
        }
    </script>
</body>

</html>