<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Retrieve members from the database
$query = "SELECT * FROM membership";
$members = $conn->query($query);
if(!$members) die("Error retrieving member data: ".$conn->error);

// Close connection to the database
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout | View All Members</title>
    <link rel="stylesheet" href="../CSS/view-all-members.css">
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
		<h1>Member List</h1>
    </div>
	
	<div class="members-container">
		<div class="top-buttons">
			<a href="user-account.php" class="back-button">Back</a>
			<a href="add-member.php" class="create-member-button">Create New Member</a>
		</div>
		
		<!-- List members -->
		<?php foreach ($members as $member): ?>
		<div class="member-item">
			<div class="member-info">
				<div class="member-info-item">
					<label for="username">Username: </label>
					<p id="username"><?php echo htmlspecialchars($member['username']); ?></p>
				</div>
				<div class="member-info-item">
					<label for="name">Name: </label>
					<p id="name"><?php echo htmlspecialchars($member['first_name']); ?> <?php echo htmlspecialchars($member['last_name']); ?></p>
				</div>
				<div class="member-info-item">
					<label for="email">Email: </label>
					<p id="email"><?php echo htmlspecialchars($member['email']); ?></p>
				</div>
			</div>
			<div class="member-button">
				<a href="view-member.php?membership_id=<?php echo htmlspecialchars($member['membership_id']); ?>" class="view-link">View</a>
				<a href="update-member.php?membership_id=<?php echo htmlspecialchars($member['membership_id']); ?>" class="update-link">Update</a>
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
            <p>This action will remove this member's profile. Do you wish to continue?</p>
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
			window.location.href = 'delete-member.php?membership_id=<?php echo htmlspecialchars($member['membership_id']); ?>';
		}

        function closeDeletePopup() {
            document.getElementById('deletePopup').style.display = 'none';
        }
    </script>
</body>

</html>