<?php
session_start();

// Set JSON header immediately
header('Content-Type: application/json; charset=utf-8');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Use the same connection as other files
require_once __DIR__ . '/connection.php';

// Check connection
if (!isset($db) || !is_object($db)) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$userId = (int)$_SESSION['user_id'];

try {
    // Enable foreign keys and start transaction
    $db->exec("PRAGMA foreign_keys = ON");
    $db->exec("BEGIN IMMEDIATE");

    // Get profile picture filename
    $stmt = $db->prepare("SELECT profile_pic FROM users WHERE id = :id");
    $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    // Delete profile picture file if it exists
    if ($row && !empty($row['profile_pic'])) {
        $profilePicPath = __DIR__ . '/uploads/profiles/' . basename($row['profile_pic']);
        if (file_exists($profilePicPath)) {
            @unlink($profilePicPath);
        }
    }

    // Delete all related data in correct order
    
    // 1. Delete gig reviews first (depends on gigs)
    $stmt = $db->prepare("DELETE FROM gig_reviews WHERE gig_id IN (SELECT id FROM gigs WHERE user_id = :id)");
    $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
    $stmt->execute();

    // 2. Delete gigs
    $stmt = $db->prepare("DELETE FROM gigs WHERE user_id = :id");
    $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
    $stmt->execute();

    // 3. Delete messages
    $stmt = $db->prepare("DELETE FROM messages WHERE sender_id = :id OR receiver_id = :id");
    $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
    $stmt->execute();

    // 4. Delete orders
    $stmt = $db->prepare("DELETE FROM orders WHERE buyer_id = :id OR seller_id = :id");
    $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
    $stmt->execute();

    // 5. Finally delete the user account
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
    $stmt->execute();

    // Commit all changes
    $db->exec("COMMIT");

    // Destroy session completely
    $_SESSION = [];
    
    // Destroy session if active
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    
    // Delete session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
    exit;

} catch (Exception $e) {
    // Rollback transaction on error
    try {
        $db->exec("ROLLBACK");
    } catch (Exception $rollbackError) {
        // Ignore rollback errors
    }
    
    // Log error
    error_log("Account deletion failed for user ID $userId: " . $e->getMessage());
    
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Failed to delete account']);
    exit;
}