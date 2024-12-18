<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Get food information
$food_id = $_GET['food_item_id'];
$query = "SELECT dish_name FROM food_item WHERE food_item_id = $food_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving dish data: ".$conn->error);
$dish = $result->fetch_array(MYSQLI_ASSOC);
$dish_name = $dish['dish_name'];

// Retrieve food item reviews
$query = "SELECT rv.*,
			COALESCE(u.username, 0) AS username
	FROM review rv
	LEFT JOIN user u ON rv.user_id = u.user_id
	WHERE food_item_id = $food_id
	GROUP BY rv.review_id";
$reviews = $conn->query($query);
if(!$reviews) die("Error retrieving reviews data: ".$conn->error);

echo <<<_END
<head>
	<title>DineScout | $dish_name Reviews</title>
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
    <link rel="stylesheet" href="../CSS/view-food-item-reviews.css">
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
	
	<div class="reviews-container">
		<div class="top-buttons">
			<a href="view-food-item.php?food_item_id=<?php echo $food_id; ?>" class="back-button">Back</a>
		</div>
		<img src="../Images/food-placeholder.jpg" class="food-image">
		<div class="food-name"><?php echo $dish_name; ?> Reviews</div>
		
		<!-- List reviews -->
		<?php foreach ($reviews as $review): ?>
		<a href="view-review.php?review_id=<?php echo htmlspecialchars($review['review_id']); ?>" class="review-info-link">
			<div class="review-info">
				<div class="review-info-item">
					<label for="username">Username: </label>
					<p id="username"><?php echo htmlspecialchars($review['username']); ?></p>
				</div>
				<div class="review-info-item">
					<label for="feedback">Review: </label>
					<p id="feedback"><?php echo htmlspecialchars($review['comment']); ?></p>
				</div>
				<div class="rating">
					<label <?php if($review['rating'] >= 1) echo 'class="rating-selected"'; ?>><?php if($review['rating'] >= 1) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
					<label <?php if($review['rating'] >= 2) echo 'class="rating-selected"'?>><?php if($review['rating'] >= 2) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
					<label <?php if($review['rating'] >= 3) echo 'class="rating-selected"'?>><?php if($review['rating'] >= 3) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
					<label <?php if($review['rating'] >= 4) echo 'class="rating-selected"'?>><?php if($review['rating'] >= 4) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
					<label <?php if($review['rating'] == 5) echo 'class="rating-selected"'?>><?php if($review['rating'] == 5) { ?>&#9733<?php } else { ?>&#9734<?php } ?></label>
				</div>
			</div>
		</a>
		<?php endforeach; ?>
	</div>

    <!-- Site Footer -->
	<footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
</body>

</html>