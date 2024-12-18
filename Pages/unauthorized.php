<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="icon" type="image/x-icon" href="../Images/ds_logo_favicon.png">
</head>
<body>
    <header class="top-bar">
        <div class="left-text">
            <a href="home.php">D | S</a>
        </div>
    </header>
    <div class="login-page">
        <h2>Unauthorized Access</h2>
        <p>You do not have permission to access this page.</p>
        <p>If you believe this is a mistake, please contact the administrator.</p>
        <div>
            <a href="home.php" class="button">Go Home</a>
        </div>
    </div>
    <footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
</body>
</html>
