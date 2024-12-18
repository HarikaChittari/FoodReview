<?php
require_once 'checksession.php';
require_once 'login_db.php';

$userId = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? null;
    $restaurantId = $data['restaurantId'] ?? 0;

    if (!$action || !$restaurantId) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit;
    }

    if ($action === 'follow') {
        $stmt = $conn->prepare("INSERT INTO followership (user_id, restaurant_id, follow_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $restaurantId);
    } elseif ($action === 'unfollow') {
        $stmt = $conn->prepare("DELETE FROM followership WHERE user_id = ? AND restaurant_id = ?");
        $stmt->bind_param("ii", $userId, $restaurantId);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        exit;
    }

    $success = $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode(['success' => $success]);
}
?>