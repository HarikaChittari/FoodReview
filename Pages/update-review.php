<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Get review information
$review_id = $_GET['review_id'];
$query = "SELECT * FROM review WHERE review_id = '$review_id'";
$result = $conn->query($query);
if(!$result) die("Error retrieving review data: ".$conn->error);
$review = $result->fetch_array(MYSQLI_ASSOC);

// Get food item information
$food_id = $review['food_item_id'];
$query = "SELECT * FROM food_item WHERE food_item_id = '$food_id'";
$result = $conn->query($query);
if(!$result) die("Error retrieving dish data: ".$conn->error);
$dish = $result->fetch_array(MYSQLI_ASSOC);
$dish_name = $dish['dish_name'];

// Get restaurant information
$rest_id = $dish['restaurant_id'];
$query = "SELECT name FROM restaurant WHERE restaurant_id = '$rest_id'";
$result = $conn->query($query);
if(!$result) die("Error retrieving restaurant data".$conn->error);
$rest = $result->fetch_array(MYSQLI_ASSOC);
$rest_name = $rest['name'];

echo <<<_END
<head>
	<title>DineScout | Update $dish_name Review</title>
</head>
_END;

// Enter review details in the database
if(isset($_POST['update'])){
	$comment = $_POST['review-comments'];
	$rating = $_POST['star'];
	
	$query = "UPDATE review SET comment='$comment', rating='$rating' WHERE review_id=$review_id";
	
	$result = $conn->query($query);
	if(!$result) die("Database connection failed: ".$conn->connect_error);
	
	// Redirect to the review page
	header("Location: view-review.php?review_id=".$review_id);
}

// Close connection to the database
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/update-review.css">
	<link rel="icon" type="image/x-icon" href="../Images/ds_logo_favicon.png">
</head>

<body>
	<header class="top-bar">
        <div class="left-text">
            <a href="home.php">D | S</a>
        </div>
        <div class="right-button">
            <a href="home.php" class="no-underline">User Account</a>
        </div>
    </header>
	
	<div class="center-logo">
        <img src="../Images/ds_logo_simple_transparent.png" alt="DineScout Logo">
		<h1>Update Review</h1>
    </div>
	
	<form action="update-review.php?review_id=<?php echo $review_id ?>" method=POST class="update-review-container">
		<div class="top-buttons">
			<a href="view-review.php?review_id=<?php echo $review_id ?>" class="cancel-button">Cancel</a>
			<button type=button class="delete-review-button" onclick="openDeletePopup()">Delete</button>
			<button type=submit class="update-review-button">Update</button>
		</div>
		<img src="../Images/food-placeholder.jpg" class="food-item-image">
		<div class="food-item-name"><?php echo $dish_name; ?></div>
		<div class="restaurant-name"><?php echo $rest_name ?></div>
		
		<div class="add-food-item-review">
			<div class="review-input-group">
				<label for="review-comments">Review:</label>
				<textarea id="review-comments" name="review-comments" required><?php echo htmlspecialchars($review['comment']); ?></textarea>
			</div>
			
			<div class="rating">
				<input id="star5" name="star" type="radio" value="5" class="radio-btn hide" <?php if($review['rating'] == 5) echo 'checked'; ?> />
				<label for="star5">&#9734</label>
				<input id="star4" name="star" type="radio" value="4" class="radio-btn hide" <?php if($review['rating'] == 4) echo 'checked'; ?> />
				<label for="star4">&#9734</label>
				<input id="star3" name="star" type="radio" value="3" class="radio-btn hide" <?php if($review['rating'] == 3) echo 'checked'; ?> />
				<label for="star3">&#9734</label>
				<input id="star2" name="star" type="radio" value="2" class="radio-btn hide" <?php if($review['rating'] == 2) echo 'checked'; ?> />
				<label for="star2">&#9734</label>
				<input id="star1" name="star" type="radio" value="1" class="radio-btn hide" <?php if($review['rating'] == 1) echo 'checked'; ?> />
				<label for="star1">&#9734</label>
			</div>
			<input type='hidden' name='update' value='yes'>
		</div>
	</form>
	
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