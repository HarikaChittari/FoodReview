<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Delete the record
if(isset($_GET['food_item_id'])){
	$food_id = $_GET['food_item_id'];
	
	$query = "SELECT restaurant_id FROM food_item WHERE food_item_id=$food_id";
	$result = $conn->query($query);
	if(!$result) die("Error connecting to the database: ".$conn->connect_error);
	$rest = $result->fetch_array(MYSQLI_ASSOC);
	$rest_id = $rest['restaurant_id'];
	
	$query = "DELETE FROM food_item WHERE food_item_id=$food_id";
	$result = $conn->query($query);
	if(!$result) die("Error removing menu item from the database: ".$conn->connect_error);
	
	header("Location: view-restaurant-menu.php?restaurant_id=$rest_id");
}

// Close the connection
$conn->close();

?>