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

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

// Retrieve the restaurants the user is following
$query = "
    SELECT r.restaurant_id, r.name, r.location, r.operating_hours, 
           COALESCE(AVG(rv.rating), 0) AS average_rating
    FROM followership fol
    INNER JOIN restaurant r ON fol.restaurant_id = r.restaurant_id
    LEFT JOIN food_item fi ON r.restaurant_id = fi.restaurant_id
    LEFT JOIN review rv ON fi.food_item_id = rv.food_item_id
    WHERE fol.user_id = ?
    GROUP BY r.restaurant_id
    ORDER BY r.name ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout | User Follows</title>
    <link rel="stylesheet" href="../CSS/view-user-follows.css">
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
        <h1>User Follows</h1>
    </div>
    
    <div class="follows-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($restaurant = $result->fetch_assoc()): ?>
                <div class="restaurant-item">
                    <img src="../Images/restaurant_logo.jpeg" alt="Restaurant Logo" class="restaurant-logo">
                    <div class="restaurant-info">
                        <h2><?php echo htmlspecialchars($restaurant['name']); ?></h2>
                        <p>Location: <?php echo htmlspecialchars($restaurant['location']); ?></p>
                        <br>
                        <p>Average Menu Ratings: <?php echo htmlspecialchars(number_format($restaurant['average_rating'], 1)); ?> stars</p>
                    </div>
                    <div class="unfollow-button">
                        <a href="view-restaurant.php?restaurant_id=<?php echo $restaurant['restaurant_id']; ?>" class="view-restaurant-link">View</a>
                        <button type="button" 
                                class="unfollow-link" 
                                data-restaurant-id="<?php echo $restaurant['restaurant_id']; ?>" 
                                onclick="toggleFollow(this, 1)">
                            Unfollow
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You are not following any restaurants.</p>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>
            Welcome: 
            <?php echo htmlspecialchars($firstName . ' ' . $lastName . " [" . $userRole . "]"); ?>
            <a href="../pages/logout.php" class="logout-button">Logout</a>
        </p>
    </footer>

    <script>
        function toggleFollow(button, isFollowing) {
            const restaurantId = button.getAttribute('data-restaurant-id');
            const action = isFollowing ? 'unfollow' : 'follow';

            fetch('follow_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action, restaurantId }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.innerText = action === 'follow' ? 'Unfollow' : 'Follow';
                        button.setAttribute('onclick', `toggleFollow(this, ${action === 'follow' ? 1 : 0})`);
          
                    } else {
                        alert('Failed to update follow status. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</body>

</html>
