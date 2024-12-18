<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Delete the record
if(isset($_GET['restaurant_id'])){
	$rest_id = $_GET['restaurant_id'];
	$query = "DELETE FROM restaurant WHERE restaurant_id=$rest_id";
	
	$result = $conn->query($query);
	if(!$result) die("Error removing restaurant from the database: ".$conn->connect_error);
	
	header("Location: home.php");
}

// Close the connection
$conn->close();

?>