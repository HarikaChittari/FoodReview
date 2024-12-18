<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Retrieve users from the database
$query = "SELECT * FROM user";
$users = $conn->query($query);
if(!$users) die("Error retrieving user data: ".$conn->error);

// Close connection to the database
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout | View All Users</title>
    <link rel="stylesheet" href="../CSS/view-all-users.css">
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
		<h1>User List</h1>
    </div>
	
	<div class="users-container">
		<div class="top-buttons">
			<a href="user-account.php" class="back-button">Back</a>
			<a href="add-user.php" class="create-user-button">Create New User</a>
		</div>
		
		<!-- List users -->
		<?php foreach ($users as $user): ?>
		<div class="user-item">
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
			<div class="user-button">
				<a href="view-user.php?user_id=<?php echo htmlspecialchars($user['user_id']); ?>" class="view-link">View</a>
				<a href="update-user.php?user_id=<?php echo htmlspecialchars($user['user_id']); ?>" class="update-link">Update</a>
				<button type=button class="delete-link" onclick="openDeletePopup()">Delete</button>
			</div>
		</div>
		<?php endforeach; ?>
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