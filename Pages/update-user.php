<?php
// Do security stuff
require_once 'checksession.php';

// Do database stuff
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

// Get user ID from session
$user_id = $_SESSION['user_id'];
if (!$user_id) {
    die("User ID is not available in the session.");
}

$firstName = $_SESSION['first_name'] ?? 'Null';
$lastName = $_SESSION['last_name'] ?? 'Null';
$userId = $_SESSION['user_id'] ?? 0;

$roleInt = $_SESSION['role'] ?? 0;
$userRole = match ($roleInt) {
    0 => 'STANDARD USER',
    1 => 'ADMIN',
    2 => 'MEMBER USER',
    default => 'Null',
};

// Fetch user information from the database
$query = "SELECT * FROM user WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("User not found.");
$user = $result->fetch_assoc();

// Sanitize data for display
$username = htmlspecialchars($user['username']);

// Update user information in the database
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $newPW = $_POST['newPW'];
    $confNewPW = $_POST['confNewPW'];

    // Compare passwords
    $fail = compare_password($newPW, $confNewPW);

    if ($fail == "") {
        $PW = password_hash($newPW, PASSWORD_DEFAULT);

        $update_query = "UPDATE user SET username = ?, email = ?, first_name = ?, last_name = ?, password = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssssi", $username, $email, $fname, $lname, $PW, $user_id);
        $result = $update_stmt->execute();

        if ($result) {
            // Redirect to the user account page
            header("Location: user-account.php");
            exit();
        } else {
            die("Database update failed: " . $conn->error);
        }
    } else {
        die($fail);
    }
}

// Compare entered passwords
function compare_password($pass1, $pass2)
{
    if ($pass1 === $pass2) return "";
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
    <title>DineScout | Update <?php echo $username; ?></title>
    <link rel="stylesheet" href="../CSS/update-user.css">
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
    
    <form action="update-user.php" method="POST" class="update-user-container">
        <div class="top-buttons">
            <a href="user-account.php" class="cancel-button">Cancel</a>
            <button type="submit" class="update-user-button">Update</button>
        </div>
        
        <div class="update-user">
            <div class="user-input-group">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="user-input-group">
                <label for="email">Email:</label>
                <input type="text" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="user-input-group">
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="user-input-group">
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div class="user-input-group">
                <label for="newPW">New Password:</label>
                <input type="password" name="newPW" required>
            </div>
            <div class="user-input-group">
                <label for="confNewPW">Confirm New Password:</label>
                <input type="password" name="confNewPW" required>
            </div>
            <input type="hidden" name="update" value="yes">
        </div>
    </form>
    
    <footer class="footer">
        <p>
            Welcome: <?php echo htmlspecialchars("$firstName $lastName [" . ($roleInt === 0 ? 'STANDARD USER' : ($roleInt === 1 ? 'ADMIN' : 'MEMBER USER')) . "]"); ?>
            <a href="../pages/logout.php" class="logout-button">Logout</a>
        </p>
    </footer>
	
	<!-- Delete Popup -->
	<div id="deletePopup" class="deletePopup">
        <div class="deletePopup-content">
            <div class="deletePopup-header">Warning</div>
            <p>This action will remove this user's profile. Do you wish to continue?</p>
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
			window.location.href = 'delete-user.php?user_id=<?php echo htmlspecialchars($user['user_id']); ?>';
		}

        function closeDeletePopup() {
            document.getElementById('deletePopup').style.display = 'none';
        }
    </script>
</body>

</html>