<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Retrieve food item reviews
$review_id = $_GET['review_id'];
$query = "SELECT rv.*,
			COALESCE(u.username, 0) AS username
	FROM review rv
	LEFT JOIN user u ON rv.user_id = u.user_id
	WHERE review_id = $review_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving review data: ".$conn->error);
$review = $result->fetch_array(MYSQLI_ASSOC);

// Get food information
$food_id = $review['food_item_id'];
$query = "SELECT * FROM food_item WHERE food_item_id = $food_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving dish data: ".$conn->error);
$dish = $result->fetch_array(MYSQLI_ASSOC);
$dish_name = $dish['dish_name'];

// Get restaurant information
$rest_id = $dish['restaurant_id'];
$query = "SELECT name FROM restaurant WHERE restaurant_id = $rest_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving restaurant data".$conn->error);
$rest = $result->fetch_array(MYSQLI_ASSOC);
$rest_name = $rest['name'];

echo <<<_END
<head>
	<title>DineScout | $dish_name Review</title>
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
    <link rel="stylesheet" href="../CSS/view-review.css">
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
	<div class="center-logo">
        <img src="../Images/ds_logo_simple_transparent.png" alt="DineScout Logo">
    </div>
	
	<div class="review-container">
		<div class="top-buttons">
			<a href="view-food-item-reviews.php?food_item_id=<?php echo $food_id; ?>" class="back-button">Back</a>
			<button type=button class="delete-review-button" onclick="openDeletePopup()">Delete</button>
			<a href="update-review.php?review_id=<?php echo $review_id; ?>" class="update-review-button">Update</a>
		</div>
		<img src="../Images/food-placeholder.jpg" class="food-item-image">
		<div class="food-item-name"><?php echo $dish_name; ?></div>
		<div class="restaurant-name"><?php echo $rest_name ?></div>

		<div class="food-item-review">
			<div class="review-user-name"><?php echo htmlspecialchars($review['username']); ?></div>
			<div class="review-feedback"><?php echo htmlspecialchars($review['comment']); ?></div>
			<div class="rating">
				<label <?php if($review['rating'] >= 1) echo 'class="rating-selected"'; ?>><?php if($review['rating'] >= 1) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
				<label <?php if($review['rating'] >= 2) echo 'class="rating-selected"'?>><?php if($review['rating'] >= 2) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
				<label <?php if($review['rating'] >= 3) echo 'class="rating-selected"'?>><?php if($review['rating'] >= 3) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
				<label <?php if($review['rating'] >= 4) echo 'class="rating-selected"'?>><?php if($review['rating'] >= 4) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
				<label <?php if($review['rating'] == 5) echo 'class="rating-selected"'?>><?php if($review['rating'] == 5) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
			</div>
		</div>
	</div>
	
	<!-- Site Footer -->
    <footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
	
	<!-- Delete Popup -->
	<div id="deletePopup" class="deletePopup">
        <div class="deletePopup-content">
            <div class="deletePopup-header">Warning</div>
            <p>This action will remove this review. Do you wish to continue?</p>
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
			window.location.href = 'delete-review.php?review_id=<?php echo $review_id; ?>';
		}

        function closeDeletePopup() {
            document.getElementById('deletePopup').style.display = 'none';
        }
    </script>
</body>

</html>