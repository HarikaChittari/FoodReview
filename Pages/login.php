<?php

// Admin. Username = bsmith Password = mysecret
// User. Username = pjones Password = acrobat
// foodcorner_member | pass1
// gourmethouse_member | pass2
// tastebuds_member | pass3
// dinewine_member | pass4
// quickbites_member | pass5

// SESSION MANAGEMENT - Create the cookie
session_start();

// Check for login, if so redirect
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

// DB login
require_once 'login_db.php';

// Sanitize functions
function mysql_entities_fix_string($conn, $string) {
    return htmlentities(mysql_fix_string($conn, $string));
}

function mysql_fix_string($conn, $string) {
    $string = stripslashes($string);
    return $conn->real_escape_string($string);
}

// DB connection
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

$error = "";

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Clean input
    $username = mysql_entities_fix_string($conn, $_POST['username']);
    $password = mysql_entities_fix_string($conn, $_POST['password']);
    
    // AUTHENTICATION admin
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    
    if ($admin && password_verify($password, $admin['password'])) {
        // AUTHORIZATION admin, 1 = admin role int
        $_SESSION['user_id'] = $admin['admin_id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = 1;
        $_SESSION['first_name'] = $admin['first_name']; // Store first name
        $_SESSION['last_name'] = $admin['last_name'];   // Store last name
        header("Location: home.php");
        exit();
    }

    // AUTHENTICATION user
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // AUTHORIZATION user, 0 = user role int
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = 0;
        $_SESSION['first_name'] = $user['first_name']; // Store first name
        $_SESSION['last_name'] = $user['last_name'];   // Store last name
        header("Location: home.php");
        exit();
    }
    
    // AUTHENTICATION membership
    $stmt = $conn->prepare("SELECT * FROM membership WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $membership = $result->fetch_assoc();

    if ($membership && password_verify($password, $membership['password'])) {
        
		// AUTHORIZATION membership, 2 = member role int
        $_SESSION['user_id'] = $membership['membership_id'];
        $_SESSION['username'] = $membership['username'];
        $_SESSION['role'] = 2; // Membership role
        $_SESSION['first_name'] = $membership['first_name']; // Store first name
        $_SESSION['last_name'] = $membership['last_name'];   // Store last name
        header("Location: home.php");
        exit();
    }

    // If no match found in either table
    $error = "Invalid username or password.";
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout Login</title>
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="icon" type="image/x-icon" href="../Images/ds_logo_favicon.png">
</head>
<body>
    <header class="top-bar">
        <div class="left-text">
            <a href="login.php">D | S</a>
        </div>
        <div class="right-button">
            <a href="login.php" class="no-underline">User Account</a>
        </div>
    </header>
    <div class="login-page">
        <img src="../Images/ds_logo_simple_transparent.png" alt="Dine Scout Logo" class="login-logo">
        <h2>DineScout<br>User Login</h2>
        <form action="login.php" method="POST">
            <?php if ($error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
    <footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
</body>
</html>

