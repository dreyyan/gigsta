<?php
session_start();
require_once __DIR__ . '/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$userId = (int)$_SESSION['user_id'];

$username = trim($_POST['username'] ?? '');
$location = trim($_POST['location'] ?? '');
$age = $_POST['age'] !== '' ? (int)$_POST['age'] : null;
$experience = (int)($_POST['experience'] ?? 0);
$tagsInput = trim($_POST['tagsInput'] ?? '');
$tags = array_filter(array_map('trim', explode(',', $tagsInput)));

if (empty($username)) {
    echo json_encode(['success' => false, 'message' => 'Username is required']);
    exit;
}

$stmt = $db->prepare("
    UPDATE users SET 
        username = ?, 
        location = ?, 
        age = ?, 
        experience_years = ?, 
        tags = ?
    WHERE id = ?
");
$stmt->bindValue(1, $username, SQLITE3_TEXT);
$stmt->bindValue(2, $location ?: null, SQLITE3_TEXT);
$stmt->bindValue(3, $age, $age !== null ? SQLITE3_INTEGER : SQLITE3_NULL);
$stmt->bindValue(4, $experience, SQLITE3_INTEGER);
$stmt->bindValue(5, json_encode($tags), SQLITE3_TEXT);
$stmt->bindValue(6, $userId, SQLITE3_INTEGER);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>