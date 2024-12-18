<?php
// DB login file
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Database connection failed: ".$conn->connect_error);

// Get member information
$member_id = $_GET['membership_id'];
$query = "SELECT * FROM membership WHERE membership_id = '$member_id'";
$result = $conn->query($query);
if(!$result) die("Error retrieving member data: ".$conn->error);
$member = $result->fetch_array(MYSQLI_ASSOC);
$username = htmlspecialchars($member['username']);

echo <<<_END
<head>
	<title>DineScout | Update $username</title>
</head>
_END;

// Update member information in the database
if(isset($_POST['update'])){
	$username = $_POST['username'];
	$email = $_POST['email'];
	$fname = $_POST['firstName'];
	$lname = $_POST['lastName'];
	$newPW = $_POST['newPW'];
	$confNewPW = $_POST['confNewPW'];
	
	$fail = compare_password($newPW,$confNewPW);
	
	if ($fail == ""){
		$PW = password_hash($newPW, PASSWORD_DEFAULT);
		
		$query = "UPDATE membership SET username='$username', email='$email', first_name='$fname', last_name='$lname', password='$PW' WHERE membership_id=$member_id";
		
		$result = $conn->query($query);
		if(!$result) die("Database connection failed: ".$conn->connect_error);
		
		// Redirect to the member view page
		header("Location: view-member.php?membership_id=".$member_id);
	}else{
		die($fail);
	}
}

// Compare entered passwords
function compare_password($pass1, $pass2){
	if($pass1==$pass2) return "";
	else return "Entered passwords do not match.\n";
}

// Close connection to the database
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/update-member.css">
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
		<h1>Update <?php echo $username; ?></h1>
    </div>
	
	<form action="update-member.php?membership_id=<?php echo $member_id; ?>" method=POST class="update-member-container">
		<div class="top-buttons">
			<a href="view-member.php?membership_id=<?php echo $member_id; ?>" class="cancel-button">Cancel</a>
			<button type=button class="delete-member-button" onclick="openDeletePopup()">Delete</button>
			<button type=submit class="update-member-button">Update</button>
		</div>
		
		<div class="update-member">
			<div class="member-input-group">
				<label for="username">Username:</label>
				<input type="text" name="username" value="<?php echo $username; ?>" required></input>
			</div>
			<div class="member-input-group">
				<label for="email">Email:</label>
				<input type="text" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required></input>
			</div>
			<div class="member-input-group">
				<label for="firstName">First Name:</label>
				<input type="text" name="firstName" value="<?php echo htmlspecialchars($member['first_name']); ?>" required></input>
			</div>
			<div class="member-input-group">
				<label for="lastName">Last Name:</label>
				<input type="text" name="lastName" value="<?php echo htmlspecialchars($member['last_name']); ?>" required></input>
			</div>
			<div class="member-input-group">
				<label for="newPW">New Password:</label>
				<input type="password" name="newPW" required></input>
			</div>
			<div class="member-input-group">
				<label for="confNewPW">Confirm New Password:</label>
				<input type="password" name="confNewPW" required></input>
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
            <p>This action will remove this member's profile. Do you wish to continue?</p>
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
			window.location.href = 'delete-member.php?membership_id=<?php echo htmlspecialchars($member['membership_id']); ?>';
		}

        function closeDeletePopup() {
            document.getElementById('deletePopup').style.display = 'none';
        }
    </script>
</body>

</html>