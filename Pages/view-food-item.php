<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Get dish details
if(!isset($_GET['food_item_id'])) die("No menu item specified");

$food_id = $_GET['food_item_id'];
$query = "SELECT * FROM food_item WHERE food_item_id = $food_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving menu item data: ".$conn->error);

$dish = $result->fetch_array(MYSQLI_ASSOC);
$rest_id = htmlspecialchars($dish['restaurant_id']);
$name = htmlspecialchars($dish['dish_name']);
$price = htmlspecialchars($dish['price']);
$description = htmlspecialchars($dish['description']);
$ingredients = htmlspecialchars($dish['ingredients']);
$nutrition = htmlspecialchars($dish['nutritional_information']);

// Get restaurant information
$query = "SELECT * FROM restaurant WHERE restaurant_id = $rest_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving restaurant data: ".$conn->error);

$restaurant = $result->fetch_array(MYSQLI_ASSOC);
$rest_name = htmlspecialchars($restaurant['name']);
$location = htmlspecialchars($restaurant['location']);

// Get restaurant information
$query = "SELECT AVG(rating) FROM review WHERE food_item_id = $food_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving rating data: ".$conn->error);

$rating = $result->fetch_array(MYSQLI_ASSOC);
if($rating['AVG(rating)'] == '') {
	$avg_rating = "0.0";
}else{
	$avg_rating = htmlspecialchars(number_format($rating['AVG(rating)'], 1));
}

echo <<<_END
<head>
	<title>DineScout | $name</title>
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
    <link rel="stylesheet" href="../CSS/view-food-item.css">
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

	<div class="food-list-container">
	<div class="top-buttons">
        <a href="view-restaurant-menu.php?restaurant_id=<?php echo $rest_id; ?>" class="back-button">Back</a>
		<button type=button class="delete-food-button" onclick="openDeletePopup()">Delete</button>
		<a href="update-food-item.php?food_item_id=<?php echo $food_id; ?>" class="update-food-button">Update</a>
    </div>
	<img src="../Images/restaurant_logo.jpeg" alt="Restaurant Logo" class="restaurant-image">
	<h1 class="restaurant-name"><?php echo $rest_name; ?></h1>
	<p class="restaurant-location">Location: <?php echo $location; ?></p>

	<div class="food-item">
		<img src="../Images/food-placeholder.jpg" class="food-image">
		<div class="food-details">
			<div class="food-name"><?php echo $name; ?></div>
			<div class="food-price">$<?php echo $price; ?></div>
		</div>
		<div class="food-description"><?php echo $description; ?></div>
		<div class="food-ingredients">Ingredients: <?php echo $ingredients; ?></div>
		<div class="food-nutrition">Nutritional Information: <?php echo $nutrition; ?></div>
		<div class="average-review">Average Rating: <?php echo $avg_rating; ?></div>
		<br>
		<div class="top-buttons">
			<a href="view-food-item-reviews.php?food_item_id=<?php echo $food_id; ?>" class="view-reviews-button">View Reviews</a>
			<a href="add-review.php?food_item_id=<?php echo $food_id; ?>" class="review-button">Review</a>
		</div>
	</div>
	</div>
	</a>
	</div>
	
	<!-- Site Footer -->
    <footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
	
	<!-- Delete Popup -->
	<div id="deletePopup" class="deletePopup">
        <div class="deletePopup-content">
            <div class="deletePopup-header">Warning</div>
            <p>This action will remove this menu item. Do you wish to continue?</p>
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
			window.location.href = 'delete-food-item.php?food_item_id=<?php echo htmlspecialchars($dish['food_item_id']); ?>';
		}

        function closeDeletePopup() {
            document.getElementById('deletePopup').style.display = 'none';
        }
    </script>
</body>

</html>