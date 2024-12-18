<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Get food item details
$food_id = $_GET['food_item_id'];
$query = "SELECT * FROM food_item WHERE food_item_id='$food_id'";
$result = $conn->query($query);
if(!$result) die("Error retrieving menu item data: ".$conn->error);

$dish = $result->fetch_array(MYSQLI_ASSOC);
$dish_name = $dish['dish_name'];

echo <<<_END
<head>
	<title>DineScout | Update $dish_name</title>
</head>
_END;

// Update dish details in the database once form is submitted
if(isset($_POST['update'])){
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
	
	$query = "UPDATE food_item SET dish_name='$name', price='$price', description='$description', ingredients='$ingredients', nutritional_information='$nutrition' WHERE food_item_id=$food_id";
	
	$result = $conn->query($query);
	if(!$result) die("Database connection failed: ".$conn->connect_error);
	
	// Redirect to the restaurant's view page
	header("Location: view-food-item.php?food_item_id=".$food_id);
}

// Close connection to the database
$conn->close();

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/update-food-item.css">
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
		<h1>Update <?php echo $dish_name; ?></h1>
    </div>
	
	<form action="update-food-item.php?food_item_id=<?php echo $food_id; ?>" method=POST class="update-food-item-container">
		<div class="top-buttons">
			<a href="view-food-item.php?food_item_id=<?php echo $food_id; ?>" class="cancel-button">Cancel</a>
			<button type=button class="delete-food-item-button" onclick="openDeletePopup()">Delete</button>
			<button type=submit class="update-food-item-button">Update</button>
		</div>
		<div class="update-food-item">
			<div class="food-item-input-group">
				<label for="dish-name">Dish Name:</label>
				<input type="text" id="dish-name" name="dish-name" value="<?php echo htmlspecialchars($dish['dish_name']); ?>" required>
			</div>
			<div class="food-item-input-group">
				<label for="price">Price:</label>
				<input type="text" id="price" name="price" value="<?php echo htmlspecialchars($dish['price']); ?>" required>
			</div>
			<div class="food-item-input-group">
				<label for="description">Description:</label>
				<textarea id="description" name="description"><?php echo htmlspecialchars($dish['description'] ?? ""); ?></textarea>
			</div>
			<div class="food-item-input-group">
				<label for="ingredients">Ingredients:</label>
				<textarea id="ingredients" name="ingredients"><?php echo htmlspecialchars($dish['ingredients'] ?? ""); ?></textarea>
			</div>
			<div class="food-item-input-group">
				<label for="nutritional-info">Nutritional Information:</label>
				<textarea id="nutritional-info" name="nutritional-info"><?php echo htmlspecialchars($dish['nutritional information'] ?? ""); ?></textarea>
			</div>
			<input type='hidden' name='update' value='yes'>
			<input type='hidden' name='food_id' value="<?php echo $food_id;?>">
		</div>
	</form>
	
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
			window.location.href = 'delete-food-item.php?food_item_id=<?php echo $food_id; ?>';
		}

        function closeDeletePopup() {
            document.getElementById('deletePopup').style.display = 'none';
        }
    </script>

</body>

</html>