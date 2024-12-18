<?php

// Do security stuff
require_once 'checksession.php';

// Do database stuff
require_once 'login_db.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

// Get current user's role and details
$roleInt = $_SESSION['role'] ?? null;
$userId = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Unknown';
$firstName = $_SESSION['first_name'] ?? 'Unknown';
$lastName = $_SESSION['last_name'] ?? 'Unknown';
$email = 'Unknown'; // Default email value

// Retrieve email from the database based on role
if ($userId !== null) {
    if ($roleInt === 1) { // Admin
        $stmt = $conn->prepare("SELECT email FROM admin WHERE admin_id = ?");
    } elseif ($roleInt === 0) { // Standard User
        $stmt = $conn->prepare("SELECT email FROM user WHERE user_id = ?");
    } elseif ($roleInt === 2) { // Member User
        $stmt = $conn->prepare("SELECT email FROM membership WHERE membership_id = ?");
    }

    if (isset($stmt)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();
    }
}

// Retrieve additional data based on role
$reviewsCount = 0;
$followsCount = 0;
$restaurants = [];

// For Standard User
if ($roleInt === 0) {
    // Count reviews written by the user
    $stmt = $conn->prepare("SELECT COUNT(*) AS reviews_count FROM review WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($reviewsCount);
    $stmt->fetch();
    $stmt->close();

    // Count restaurants followed by the user
    $stmt = $conn->prepare("SELECT COUNT(*) AS follows_count FROM followership WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($followsCount);
    $stmt->fetch();
    $stmt->close();
}

// For Member User
if ($roleInt === 2) {
    // Retrieve restaurants added by the member with follower counts
    $stmt = $conn->prepare("
    SELECT 
        r.restaurant_id,  -- Add restaurant_id to the select query
        r.name, 
        r.location, 
        COUNT(f.follow_id) AS followers
    FROM restaurant r
    LEFT JOIN followership f ON r.restaurant_id = f.restaurant_id
    WHERE r.membership_id = ?
    GROUP BY r.restaurant_id
");

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $restaurants[] = $row;
    }
    $stmt->close();
}

// Ensure no null values are passed to the output
$firstName = $firstName ?? 'Unknown';
$lastName = $lastName ?? 'Unknown';
$email = $email ?? 'Unknown';

// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout | User Account</title>
    <link rel="stylesheet" href="../CSS/user-account.css">
    <link rel="icon" type="image/x-icon" href="../Images/ds_logo_favicon.png">
</head>
<body>
    <header class="top-bar">
        <div class="left-text">
            <a href="home.php">D | S</a>
        </div>
        <div class="right-button">
            <a href="home.php" class="no-underline">Home</a>
        </div>
    </header>
    <div class="content">
        <img src="../Images/ds_logo_simple_transparent.png" alt="DineScout Logo" class="center-logo">
        <h1>User Account Center</h1>
        <br>

        <?php if ($roleInt === 1): ?> <!-- Admin Role -->
    <div class="profile-box">
        <button class="update-profile-button" onclick="window.location.href='update-admin.php'">Update Profile</button>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars("$firstName $lastName"); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    </div>
	<br>
    <div class="user-management">
        <h2>User Management</h2>
        <button onclick="window.location.href='view-all-users.php'">View Users</button>
        <button onclick="window.location.href='add-user.php'">Create User</button>
    </div>
	<br>
    <div class="member-management">
        <h2>Member Management</h2>
        <button onclick="window.location.href='view-all-members.php'">View Members</button>
        <button onclick="window.location.href='add-member.php'">Create Member</button>
    </div>
<?php elseif ($roleInt === 0): ?> <!-- Standard User Role -->
    <div class="profile-box">
        <button class="update-profile-button" onclick="window.location.href='update-user.php'">Update My Profile</button>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars("$firstName $lastName"); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    </div>
	<br>
    <div class="reviews-box">
        <button onclick="window.location.href='view-user-reviews.php'">View My Food Reviews</button>
        <p>Reviews Written: <?php echo htmlspecialchars($reviewsCount); ?></p>
    </div>
	<br>
    <div class="follows-box">
        <button onclick="window.location.href='view-user-follows.php'">View My Restaurant Follows</button>
        <p>Restaurants Followed: <?php echo htmlspecialchars($followsCount); ?></p>
    </div>
<?php elseif ($roleInt === 2): ?> <!-- Member Role -->
    <div class="profile-box">
        <button class="update-profile-button" onclick="window.location.href='update-member.php'">Update Profile</button>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars("$firstName $lastName"); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <button class="add-restaurant-button" onclick="window.location.href='add-restaurant.php'">Add Restaurant</button>
    </div>
	<br>
    <div class="restaurants-box">
        <?php foreach ($restaurants as $restaurant): ?>
            <div class="restaurant-item">
                <div class="restaurant-item">
					<button class="view-restaurant-button" 
						onclick="window.location.href='view-restaurant.php?restaurant_id=<?php echo urlencode($restaurant['restaurant_id']); ?>'">
					View Restaurant
				</button>
				<p><strong>Name:</strong> <?php echo htmlspecialchars($restaurant['name']); ?></p>
				<p><strong>Location:</strong> <?php echo htmlspecialchars($restaurant['location']); ?></p>
				<p><strong>Followers:</strong> <?php echo htmlspecialchars($restaurant['followers']); ?></p>
</div>

            </div><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

        </div>
    </div>
    <footer class="footer">
        <p>
            Welcome: <?php echo htmlspecialchars("$firstName $lastName [" . ($roleInt === 0 ? 'STANDARD USER' : ($roleInt === 1 ? 'ADMIN' : 'MEMBER USER')) . "]"); ?>
            <a href="../pages/logout.php" class="logout-button">Logout</a>
        </p>
    </footer>
</body>
</html>