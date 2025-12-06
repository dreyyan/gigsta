<?php
session_start();
require_once __DIR__ . '/../database/connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Error: Not logged in.");
}

$userId = (int)$_SESSION['user_id'];

// === GET DATA ===
$role             = trim($_POST['role'] ?? '');
$age              = !empty($_POST['age']) ? (int)$_POST['age'] : null;
$location         = trim($_POST['location'] ?? '');
$experience_years = !empty($_POST['experience_years']) ? (int)$_POST['experience_years'] : 0;
$tags             = is_array($_POST['tags'] ?? []) ? array_slice($_POST['tags'], 0, 5) : [];

// === VALIDATION ===
if (!in_array($role, ['client', 'gigster'])) {
    die("Please choose a role.");
}
if ($age !== null && ($age < 13 || $age > 100)) {
    die("Invalid age.");
}
if (empty($location)) {
    die("Location is required.");
}

// === ADD TAGS COLUMN SAFELY ===
try {
    $db->exec("ALTER TABLE users ADD COLUMN tags TEXT");
} catch (Exception $e) {
    // Column already exists
}

// === SAVE TO DATABASE ===
try {
    $db->exec("BEGIN TRANSACTION");

    $stmt = $db->prepare("
        UPDATE users SET
            role = ?,
            age = ?,
            location = ?,
            experience_years = ?,
            onboarded = 1,
            tags = ?
        WHERE id = ?
    ");

    $tagsJson = $role === 'gigster' && !empty($tags) ? json_encode($tags) : null;

    $stmt->bindValue(1, $role, SQLITE3_TEXT);
    $stmt->bindValue(2, $age, $age !== null ? SQLITE3_INTEGER : SQLITE3_NULL);
    $stmt->bindValue(3, $location, SQLITE3_TEXT);
    $stmt->bindValue(4, $experience_years, SQLITE3_INTEGER);
    $stmt->bindValue(5, $tagsJson, $tagsJson ? SQLITE3_TEXT : SQLITE3_NULL);
    $stmt->bindValue(6, $userId, SQLITE3_INTEGER);

    $stmt->execute();
    $db->exec("COMMIT");

    // Success!
    $_SESSION['onboarding_complete'] = true;
    $_SESSION['user_role'] = $role;

    header("Location: " . ($role === 'gigster' ? '../pages/CreateGig.php' : '../pages/BrowseGigs.php'));
    exit;

} catch (Exception $e) {
    $db->exec("ROLLBACK");
    die("Save failed: " . $e->getMessage());
}
?>