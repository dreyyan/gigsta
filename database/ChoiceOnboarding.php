<?php
session_start();
require_once __DIR__ . '/../database/db.php';  

// Make sure user is logged in first
if (!isset($_SESSION['user_id'])) {
    die("Not logged in");
}

$role = $_POST['role'];

if ($role !== "client" && $role !== "gigster") {
    die("Invalid role");
}

// Update role in the database
$stmt = $db->prepare("UPDATE users SET role = :role WHERE id = :id");
$stmt->bindValue(':role', $role, SQLITE3_TEXT);
$stmt->bindValue(':id', $_SESSION['user_id'], SQLITE3_INTEGER);
$stmt->execute();

// Redirect to next onboarding step
header("Location: NextStep.php");
exit;