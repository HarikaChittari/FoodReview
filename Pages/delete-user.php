<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Delete the record
if(isset($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	
	$query = "DELETE FROM user WHERE user_id=$user_id";
	$result = $conn->query($query);
	if(!$result) die("Error removing user from the database: ".$conn->connect_error);
	
	header("Location: user-account.php");
}

// Close the connection
$conn->close();
?>