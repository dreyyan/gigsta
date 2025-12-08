<?php
// database/create_order.php
session_start();
require_once __DIR__ . '/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$gigId = $_POST['gig_id'] ?? 0;
$sellerId = $_POST['seller_id'] ?? 0;
$buyerId = (int)$_SESSION['user_id'];

if ($gigId <= 0 || $sellerId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid gig or seller']);
    exit;
}

// Verify gig belongs to seller
$stmt = $db->prepare("SELECT id FROM gigs WHERE id = ? AND user_id = ?");
$stmt->bindValue(1, $gigId, SQLITE3_INTEGER);
$stmt->bindValue(2, $sellerId, SQLITE3_INTEGER);
$result = $stmt->execute();
if (!$result->fetchArray()) {
    echo json_encode(['success' => false, 'message' => 'Gig not found']);
    exit;
}

// Insert order
$stmt = $db->prepare("
    INSERT INTO orders (buyer_id, gig_id, status, created_at) 
    VALUES (?, ?, 'pending', datetime('now'))
");
$stmt->bindValue(1, $buyerId, SQLITE3_INTEGER);
$stmt->bindValue(2, $gigId, SQLITE3_INTEGER);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>