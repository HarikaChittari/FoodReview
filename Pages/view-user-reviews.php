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

// Retrieve the reviews for the logged-in user
$query = "
    SELECT r.review_id, res.name AS restaurant_name, fi.dish_name, r.comment, r.rating
    FROM review r
    INNER JOIN food_item fi ON r.food_item_id = fi.food_item_id
    INNER JOIN restaurant res ON fi.restaurant_id = res.restaurant_id
    WHERE r.user_id = ?
    ORDER BY r.review_date DESC";
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
    <title>DineScout | User Reviews</title>
    <link rel="stylesheet" href="../CSS/view-user-reviews.css">
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
        <h1>User Reviews</h1>
    </div>
    
    <div class="reviews-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($review = $result->fetch_assoc()): ?>
                <div class="review-item">
                    <div class="review-info">
                        <div class="review-info-item">
                            <label for="restaurant">Restaurant: </label>
                            <p id="restaurant"><?php echo htmlspecialchars($review['restaurant_name']); ?></p>
                        </div>
                        <div class="review-info-item">
                            <label for="foodItem">Dish: </label>
                            <p id="foodItem"><?php echo htmlspecialchars($review['dish_name']); ?></p>
                        </div>
                        <div class="review-info-item">
                            <label for="feedback">Review: </label>
                            <p id="feedback"><?php echo htmlspecialchars($review['comment']); ?></p>
                        </div>
                        <br>
                        <div class="rating">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $review['rating'] ? '<label class="rating-selected">&#9733;</label>' : '<label>&#9734;</label>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="review-button">
                        <a href="view-review.php?review_id=<?php echo $review['review_id']; ?>" class="view-review-link">View My Review</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have not submitted any reviews.</p>
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
