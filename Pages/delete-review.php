<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Delete the record
if(isset($_GET['review_id'])){
	$review_id = $_GET['review_id'];
	
	$query = "DELETE FROM review WHERE review_id=$review_id";
	$result = $conn->query($query);
	if(!$result) die("Error removing review from the database: ".$conn->connect_error);
	
	header("Location: user-account.php");
}

// Close the connection
$conn->close();
?>