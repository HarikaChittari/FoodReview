<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Retrieve member information
$member_id = $_GET['membership_id'];
$query = "SELECT * FROM membership WHERE membership_id = $member_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving member data: ".$conn->error);
$member = $result->fetch_array(MYSQLI_ASSOC);
$username = htmlspecialchars($member['username']);

// Retrieve restaurant information
$query = "SELECT r.*,
			COALESCE(COUNT(DISTINCT(fol.follow_id)), 0) AS followers
	FROM restaurant r
	LEFT JOIN followership fol ON r.restaurant_id = fol.restaurant_id
	WHERE membership_id = $member_id";
$restaurants = $conn->query($query);
if(!$result) die("Error retrieving restaurant data".$conn->error);

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
    <link rel="stylesheet" href="../CSS/view-member.css">
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
		<h1>View Member</h1>
    </div>
	
	<div class="member-container">
		<div class="top-buttons">
			<a href="view-all-members.php" class="back-button">Back</a>
			<button type=button class="delete-member-button" onclick="openDeletePopup()">Delete</button>
			<a href="update-member.php?membership_id=<?php echo $member_id ?>" class="update-member-button">Update</a>
		</div>

		<div class="member-info">
			<div class="member-info-item">
				<label for="username">Username: </label>
				<p id="username"><?php echo $username ?></p>
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
		
		<!-- List restaurants -->
		<?php foreach ($restaurants as $restaurant): ?>
		<?php if($restaurant['name'] =="") { }else{ ?>
		<div class="restaurant-info">
			<div class="restaurant-name"><p><?php echo htmlspecialchars($restaurant['name']); ?></p></div>
			<div class="restaurant-info-item">
				<label for="location">Location: </label>
				<p id="location"><?php echo htmlspecialchars($restaurant['location']); ?></p>
			</div>
			<div class="restaurant-info-item">
				<label for="followers">Followers: </label>
				<p id="followers"><?php echo htmlspecialchars($restaurant['followers']); ?></p>
			</div>
			<div class="view-restaurant-button">
				<a href="view-restaurant.php?restaurant_id=<?php echo htmlspecialchars($restaurant['restaurant_id']); ?>" class="view-restaurant-link">View Restaurant</a>
			</div>
		</div>
		<?php } ?>
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