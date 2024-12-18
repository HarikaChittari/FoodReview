<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Enter restaurant details in the database
if(isset($_POST['dish-name'])){
	$rest_id = $_POST['rest_id'];
	$name = $_POST['dish-name'];
	$price = $_POST['price'];
	if(isset($_POST['description'])) {
		$description = $_POST['description'];
	}else{
		$description = NULL;
	}
	if(isset($_POST['ingredients'])) {
		$ingredients = $_POST['ingredients'];
	}else{
		$ingredients = NULL;
	}
	if(isset($_POST['nutritional-info'])) {
		$nutrition = $_POST['nutritional-info'];
	}else{
		$nutrition = NULL;
	}
	
	$query = "INSERT INTO food_item (restaurant_id, dish_name, price, description, ingredients, nutritional_information) VALUES('$rest_id', '$name', '$price', '$description', '$ingredients', '$nutrition')";
	
	$result = $conn->query($query);
	if(!$result) die("Database connection failed: ".$conn->connect_error);
	
	// Redirect to the new restaurant's view page
	$last_id = $conn->insert_id;
	header("Location: view-food-item.php?food_item_id=".$last_id);
}

// Close connection to the database
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout | Create New Dish</title>
    <link rel="stylesheet" href="../CSS/add-food-item.css">
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
	
	<!-- Main Site Content -->
	<div class="center-logo">
        <img src="../Images/ds_logo_simple_transparent.png" alt="DineScout Logo">
		<h1>Create New Dish</h1>
    </div>
	
	<form action="add-food-item.php?restaurant_id=<?php echo htmlspecialchars($_GET['restaurant_id'] && '0'); ?>" method=POST class="add-food-item-container">
		<div class="top-buttons">
			<button type=button class="cancel-button" onclick="window.location.href = 'view-restaurant-menu.php?restaurant_id=<?php echo htmlspecialchars($_GET['restaurant_id']); ?>';">Cancel</button>
			<button type=submit class="create-food-item-button">Create</button>
		</div>
		<div class="add-food-item">
			<div class="food-item-input-group">
				<label for="dish-name">Dish Name:</label>
				<input type="text" id="dish-name" name="dish-name" required>
			</div>
			<div class="food-item-input-group">
				<label for="price">Price:</label>
				<input type="text" id="price" name="price" placeholder="$" required>
			</div>
			<div class="food-item-input-group">
				<label for="description">Description:</label>
				<textarea id="description" name="description"></textarea>
			</div>
			<div class="food-item-input-group">
				<label for="ingredients">Ingredients:</label>
				<textarea id="ingredients" name="ingredients"></textarea>
			</div>
			<div class="food-item-input-group">
				<label for="nutritional-info">Nutritional Information:</label>
				<textarea id="nutritional-info" name="nutritional-info"></textarea>
			</div>
			<input type=hidden name="rest_id" value="<?php echo htmlspecialchars($_GET['restaurant_id']); ?>">
		</div>
	</form>
	
	<footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>

</body>

</html>