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

$query = "
    SELECT r.*, 
           COALESCE(AVG(rv.rating), 0) AS average_rating,
           COALESCE(COUNT(DISTINCT fol.follow_id), 0) AS followers,
           CASE WHEN EXISTS (
               SELECT 1 FROM followership fol WHERE fol.user_id = $userId AND fol.restaurant_id = r.restaurant_id
           ) THEN 1 ELSE 0 END AS is_following
    FROM restaurant r
    LEFT JOIN food_item fi ON r.restaurant_id = fi.restaurant_id
    LEFT JOIN review rv ON fi.food_item_id = rv.food_item_id
    LEFT JOIN followership fol ON r.restaurant_id = fol.restaurant_id
    GROUP BY r.restaurant_id";
$restaurants = $conn->query($query);
if (!$restaurants) die("Error retrieving restaurant data: " . $conn->error);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DineScout | Home</title>
    <link rel="stylesheet" href="../CSS/home.css">
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
    
    <div class="content">
        <img src="../Images/ds_logo_simple_transparent.png" alt="DineScout Logo" class="center-logo">
        <h1>Local Restaurants</h1>
        <p>Near Salt Lake City | 84112</p>
        <br>
        
        <div class="restaurant-list">
            <?php foreach ($restaurants as $restaurant): ?>
                <div class="restaurant-item">
                    <img src="../Images/restaurant_logo.jpeg" alt="Restaurant Logo" class="restaurant-logo">
                    <div class="restaurant-info">
                        <h2><?php echo htmlspecialchars($restaurant['name'] ?? 'Unknown Restaurant'); ?></h2>
                        <p>Description: <?php echo htmlspecialchars($restaurant['description'] ?? 'No description available'); ?></p>
                        <p>Location: <?php echo htmlspecialchars($restaurant['location'] ?? 'Location unavailable'); ?></p>
                        <p>Hours: <?php echo htmlspecialchars($restaurant['operating_hours'] ?? 'No hours provided'); ?></p>
                        <p>Followers: <?php echo htmlspecialchars($restaurant['followers'] ?? '0'); ?></p>
                        <p>Average Rating: <?php echo htmlspecialchars(number_format($restaurant['average_rating'] ?? 0, 1)); ?> stars</p>
                    </div>
                    <div class="follow-button">
                        <a href="view-restaurant.php?restaurant_id=<?php echo htmlspecialchars($restaurant['restaurant_id'] ?? '0'); ?>" class="food-reviews-button">View Restaurant</a>
                        <?php if ($roleInt === 0): ?>
                            <button type="button" 
                                    class="follow-button-link" 
                                    data-restaurant-id="<?php echo $restaurant['restaurant_id']; ?>" 
                                    onclick="toggleFollow(this, <?php echo $restaurant['is_following']; ?>)">
                                <?php echo $restaurant['is_following'] ? 'Unfollow' : 'Follow'; ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <br><br><br>
            <?php endforeach; ?>
        </div>
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



