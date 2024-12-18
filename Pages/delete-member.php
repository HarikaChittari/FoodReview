<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Delete the record
if(isset($_GET['membership_id'])){
	$member_id = $_GET['membership_id'];
	
	$query = "DELETE FROM membership WHERE membership_id=$member_id";
	$result = $conn->query($query);
	if(!$result) die("Error removing member from the database: ".$conn->connect_error);
	
	header("Location: user-account.php");
}

// Close the connection
$conn->close();
?>