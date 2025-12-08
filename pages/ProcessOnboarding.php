<?php
session_start();

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/Login.php');
    exit;
}

try {
    $db = new SQLite3(__DIR__ . '/database.db');
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['role'])) {
    $user_id = $_SESSION['user_id'];
    $role = $_POST['role']; // 'client' or 'gigster'

    if (!in_array($role, ['client', 'gigster'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid role']);
        exit;
    }

    $stmt = $db->prepare("UPDATE users SET role = :role, onboarded = 1 WHERE id = :id");
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    $stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);

    $result = $stmt->execute();
    if ($result) {
        // Redirect based on role
        if ($role === 'gigster') {
            header('Location: onboarding-step2-gigster.php');
        } else {
            header('Location: /pages/BrowseGigs.php');
        }
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to update onboarding info']);
        exit;
    }
} else {
    // If no POST data, redirect back
    header('Location: /pages/Onboarding.php');
    exit;
}
?>
