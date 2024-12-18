<?php
// DB login file
require_once 'checksession.php';
require_once 'login_db.php';

// Get current user's role
$roleInt = $_SESSION['role'] ?? 0;
$userRole = match ($roleInt) {
    0 => 'STANDARD USER',
    1 => 'ADMIN',
    2 => 'MEMBER USER',
    default => 'Null',
};

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

// Get restaurant ID
if (!isset($_GET['restaurant_id']) || !is_numeric($_GET['restaurant_id'])) {
    die("Invalid or missing restaurant ID.");
}
$rest_id = (int)$_GET['restaurant_id'];

// Fetch restaurant details
$query = "SELECT * FROM restaurant WHERE restaurant_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rest_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Restaurant not found.");
$restaurant = $result->fetch_assoc();

// Check if the user is following the restaurant
$is_following = 0; // Default to not following
if ($roleInt === 0) { // Standard User Role
    $user_id = $_SESSION['user_id'] ?? null;
    if ($user_id) {
        $follow_query = "SELECT COUNT(*) FROM followership WHERE user_id = ? AND restaurant_id = ?";
        $follow_stmt = $conn->prepare($follow_query);
        $follow_stmt->bind_param("ii", $user_id, $rest_id);
        $follow_stmt->execute();
        $follow_stmt->bind_result($is_following_count);
        $follow_stmt->fetch();
        $is_following = $is_following_count > 0 ? 1 : 0;
        $follow_stmt->close();
    }
}

$name = htmlspecialchars($restaurant['name']);
$stmt->close();
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/view-restaurant.css">
    <link rel="icon" type="image/x-icon" href="../Images/ds_logo_favicon.png">
</head>

<body>
	<!-- Site Header -->
    <header class="header">
        <div class="logo">
            <a href="home.php">D | S</a>
        </div>
        <div class="user-account">
            <a href="user-account.php" class="button">User Account</a>
        </div>
    </header>
	
	<!-- Site Main Content -->
    <main>
        <section class="branding">
            <img src="../Images/ds_logo_simple_transparent.png" alt="DineScout Logo" class="branding-logo">
        </section>
		<br>
        <section class="restaurant-info">
            <div class="top-buttons">
                <a href="update-restaurant.php?restaurant_id=<?php echo htmlspecialchars($restaurant['restaurant_id'] ?? '0'); ?>" class="update-restaurant-button">Update</a>
				<button type=button class="delete-restaurant-button" onclick="openDeletePopup()">Delete</button>
                <?php if ($roleInt === 0): ?>
					<button type="button" 
        class="follow-button-link" 
        data-restaurant-id="<?php echo $rest_id; ?>" 
        onclick="toggleFollow(this, <?php echo $is_following; ?>)">
    <?php echo $is_following ? 'Unfollow' : 'Follow'; ?>
</button>

				<?php endif; ?>
            </div>
			<br>
            <img src="../Images/restaurant_logo.jpeg" alt="Restaurant Logo" class="restaurant-logo">
            <h2 class="restaurant-name"><?php echo htmlspecialchars($restaurant['name']) ?? 'Restaurant Not Found' ?></h2>
			<br>
            <p class="restaurant-description">Description: <?php echo htmlspecialchars($restaurant['description'] ?? 'No description available'); ?></p>
            <p class="restaurant-location">Location: <?php echo htmlspecialchars($restaurant['location'] ?? 'Location unavailable'); ?></p>
            <p class="restaurant-website"><a href="<?php echo $restaurant['website']?>"><?php echo htmlspecialchars($restaurant['website'] ?? 'Website not defined'); ?></a></p>
            <p class="restaurant-contact">Phone: <?php echo htmlspecialchars($restaurant['phone_number'] ?? 'No phone number provided'); ?> | Email: <?php echo htmlspecialchars($restaurant['email'] ?? 'No email provided'); ?></p>
            <p class="restaurant-hours">Operating Hours: <?php echo htmlspecialchars($restaurant['operating_hours'] ?? 'No hours provided'); ?></p>
			<br>
            <div class="menu-buttons">
                <a href="view-restaurant-followers.php?restaurant_id=<?php echo htmlspecialchars($restaurant['restaurant_id']) ?? '0' ?>" class="followers-button">View Followers</a>
                <a href="view-restaurant-menu.php?restaurant_id=<?php echo htmlspecialchars($restaurant['restaurant_id']) ?? '0' ?>" class="view-menu-button">View Menu</a>
            </div>
        </section>
    </main>

	<!-- Site Footer -->
    <footer class="footer">
        <p>All rights reserved DineScout LLC | Stucki, Frandsen, Chittari</p>
    </footer>
	
	<!-- Delete Popup -->
	<div id="deletePopup" class="deletePopup">
        <div class="deletePopup-content">
            <div class="deletePopup-header">Warning</div>
            <p>This action will remove this restaurant. Do you wish to continue?</p>
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
			window.location.href = 'delete-restaurant.php?restaurant_id=<?php echo htmlspecialchars($restaurant['restaurant_id']); ?>';
		}

        function closeDeletePopup() {
            document.getElementById('deletePopup').style.display = 'none';
        }
		
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
