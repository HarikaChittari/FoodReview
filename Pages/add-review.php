<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Get food item information
$food_id = $_GET['food_item_id'];
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
	<title>DineScout | Create $dish_name Review</title>
</head>
_END;

// Enter review details in the database
if(isset($_POST['review-comments'])){
	$food_id = $_POST['food_id'];
	$comment = $_POST['review-comments'];
	$rating = $_POST['star'];

	$query = "INSERT INTO review (food_item_id, comment, rating) VALUES('$food_id', '$comment', '$rating')";
	
	$result = $conn->query($query);
	if(!$result) die("Database connection failed: ".$conn->connect_error);
	
	// Redirect to the new restaurant's view page
	$last_id = $conn->insert_id;
	header("Location: view-review.php?review_id=".$last_id);
}

// Close connection to the database
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/add-review.css">
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
		<h1>Create New Review</h1>
    </div>
	
	<form action="add-review.php?food_item_id=<?php echo $food_id; ?>" method=post class="add-review-container">
		<div class="top-buttons">
			<button type=button class="cancel-button" onclick="window.location.href = 'view-food-item.php?food_item_id=<?php echo $food_id; ?>';">Cancel</button>
			<button type=submit class="create-review-button">Create</a>
		</div>
		<img src="../Images/food-placeholder.jpg" class="food-item-image">
		<div class="food-item-name"><?php echo $dish_name; ?></div>
		<div class="restaurant-name"><?php echo $rest_name ?></div>
		
		<div class="add-food-item-review">
			<div class="review-input-group">
				<label for="review-comments">Review:</label>
				<textarea id="review-comments" name="review-comments" required></textarea>
			</div>
			
			<div class="rating">
				<input id="star5" name="star" type="radio" value="5" class="radio-btn hide" />
				<label for="star5">&#9734</label>
				<input id="star4" name="star" type="radio" value="4" class="radio-btn hide" />
				<label for="star4">&#9734</label>
				<input id="star3" name="star" type="radio" value="3" class="radio-btn hide" />
				<label for="star3">&#9734</label>
				<input id="star2" name="star" type="radio" value="2" class="radio-btn hide" />
				<label for="star2">&#9734</label>
				<input id="star1" name="star" type="radio" value="1" class="radio-btn hide" />
				<label for="star1">&#9734</label>
			</div>
			<input type=hidden name="food_id" value="<?php echo htmlspecialchars($_GET['food_item_id']); ?>">
		</div>
	</form>
	
	<footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>

</body>

</html>