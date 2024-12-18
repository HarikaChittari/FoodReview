<?php

require_once 'checksession.php';
require_once 'login_db.php';

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

$restaurantId = $_GET['restaurant_id'] ?? 0;

if (!$restaurantId || !is_numeric($restaurantId)) {
    die("Invalid restaurant ID.");
}

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

// Fetch restaurant name
$restaurantQuery = $conn->prepare("SELECT name FROM restaurant WHERE restaurant_id = ?");
$restaurantQuery->bind_param("i", $restaurantId);
$restaurantQuery->execute();
$restaurantResult = $restaurantQuery->get_result();
$restaurantName = $restaurantResult->fetch_assoc()['name'] ?? 'Unknown Restaurant';
$restaurantQuery->close();

// Fetch followers
$followersQuery = $conn->prepare("
    SELECT u.username, u.first_name, u.last_name, fol.follow_date
    FROM followership fol
    INNER JOIN user u ON fol.user_id = u.user_id
    WHERE fol.restaurant_id = ?
    ORDER BY fol.follow_date DESC
");
$followersQuery->bind_param("i", $restaurantId);
$followersQuery->execute();
$followersResult = $followersQuery->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout | <?php echo htmlspecialchars($restaurantName); ?> Followers</title>
    <link rel="stylesheet" href="../CSS/view-restaurant-followers.css">
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
    </div>
    
    <div class="followers-container">
        <img src="../Images/restaurant_logo.jpeg" alt="<?php echo htmlspecialchars($restaurantName); ?>" class="restaurant-image">
        <div class="restaurant-name"><?php echo htmlspecialchars($restaurantName); ?> Followers</div>
        
        <?php if ($followersResult->num_rows > 0): ?>
            <?php while ($follower = $followersResult->fetch_assoc()): ?>
                <a href="view-user.php?username=<?php echo htmlspecialchars($follower['username']); ?>" class="user-info-link">
                    <div class="user-info">
                        <div class="user-info-item">
                            <label for="username">Username: </label>
                            <p id="username"><?php echo htmlspecialchars($follower['username']); ?></p>
                        </div>
                        <div class="user-info-item">
                            <label for="follows">Following: </label>
                            <p id="follows">Followed on <?php echo htmlspecialchars($follower['follow_date']); ?></p>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No followers for this restaurant yet.</p>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>
            Welcome: 
            <?php echo htmlspecialchars($firstName . ' ' . $lastName . " [" . $userRole . "]"); ?>
            <a href="../pages/logout.php" class="logout-button">Logout</a>
        </p>
    </footer>
</body>

</html>