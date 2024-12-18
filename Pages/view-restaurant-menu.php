<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Retrieve restaurant data
$rest_id = $_GET['restaurant_id'];
$query = "SELECT * FROM restaurant WHERE restaurant_id = $rest_id";
$result = $conn->query($query);
if(!$result) die("Error retrieving restaurant data: ".$conn->error);
$restaurant = $result->fetch_array(MYSQLI_ASSOC);
$rest_name = $restaurant['name'];
$location = $restaurant['location'];

// Retrieve restaurant menu data
$query = "SELECT fi.*,
			COALESCE(AVG(rv.rating), 0) AS average_rating
	FROM food_item fi
	LEFT JOIN review rv ON fi.food_item_id = rv.food_item_id
	WHERE restaurant_id = $rest_id
	GROUP BY fi.food_item_id";
$menu = $conn->query($query);
if(!$menu) die("Error retrieving menu data: ".$conn->error);

echo <<<_END
<head>
	<title>DineScout | View $rest_name</title>
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
    <link rel="stylesheet" href="../CSS/view-restaurant-menu.css">
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
		<a href="view-restaurant.php?restaurant_id=<?php echo $rest_id; ?>" class="back-button">Back</a>
        <a href="add-food-item.php?restaurant_id=<?php echo $rest_id; ?>" class="add-food-button">Add Item</a>
    </div>
	<img src="../Images/restaurant_logo.jpeg" alt="Restaurant Logo" class="restaurant-image">
	<h1 class="restaurant-name"><?php echo $rest_name; ?> Menu</h1>
	<p class="restaurant-location">Location: <?php echo $location; ?></p>

	<!-- List menu items -->
	<?php foreach ($menu as $dish): ?>
	<a href="view-food-item.php?food_item_id=<?php echo htmlspecialchars($dish['food_item_id'] ?? '0'); ?>" class="food-item-link">
	<div class="food-item">
		<img src="../Images/food-placeholder.jpg" class="food-image">
		<div class="food-details">
			<div class="food-name"><?php echo htmlspecialchars($dish['dish_name'] ?? 'Unknown Dish'); ?></div>
			<div class="food-price">$<?php echo htmlspecialchars($dish['price'] ?? 'No price provided'); ?></div>
		</div>
		<div class="food-description"><?php echo htmlspecialchars($dish['description'] ?? '0'); ?></div>
		<div class="average-review">Average Review: <?php echo htmlspecialchars(number_format($dish['average_rating'], 1) ?? '0.0'); ?> stars</div>
	</div>
	</a>
	<?php endforeach; ?>
	
	<!-- Site Footer -->
    <footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
</body>

</html>

