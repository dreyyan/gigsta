<?php
session_start();
require_once __DIR__ . '/../database/connection.php';  // Make sure this points to your SQLite connection

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in.");
}

$userId = (int)$_SESSION['user_id'];

// === Validate & sanitize input ===
$role = trim($_POST['role'] ?? '');
$age = (int)($_POST['age'] ?? 0);
$location = trim($_POST['location'] ?? '');
$experience_years = (int)($_POST['experience_years'] ?? 0);
$tags = $_POST['tags'] ?? [];

// Basic validation
if (!in_array($role, ['client', 'gigster'])) {
    die("Invalid role selected.");
}
if ($age < 13 || $age > 100) {
    die("Please enter a valid age (13–100).");
}
if (empty($location)) {
    die("Location is required.");
}
if ($experience_years < 0 || $experience_years > 60) {
    die("Invalid experience years.");
}
if (!is_array($tags)) {
    $tags = [];
}

// === Start database transaction (safe atomic update) ===
$db->exec("BEGIN TRANSACTION");

try {
    // 1. Update main user info
    $stmt = $db->prepare("
        UPDATE users 
        SET role = :role,
            age = :age,
            location = :location,
            experience_years = :experience_years,
            onboarded = 1
        WHERE id = :id
    ");
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':location', $location, SQLITE3_TEXT);
    $stmt->bindValue(':experience_years', $experience_years, SQLITE3_INTEGER);
    $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
    $stmt->execute();

    // 2. Handle talent tags (only for gigsters)
    if ($role === 'gigster' && !empty($tags)) {
        // Clear old tags
        $db->exec("DELETE FROM user_tags WHERE user_id = $userId");

        // Insert new ones
        $tagStmt = $db->prepare("
            INSERT OR IGNORE INTO tags (name) VALUES (?)
        ");
        $linkStmt = $db->prepare("
            INSERT OR IGNORE INTO user_tags (user_id, tag_id) 
            VALUES (:user_id, (SELECT id FROM tags WHERE name = :tag))
        ");

        foreach ($tags as $tagName) {
            $tagName = trim($tagName);
            if ($tagName === '') continue;

            // Insert tag if not exists
            $tagStmt->bindValue(1, $tagName, SQLITE3_TEXT);
            $tagStmt->execute();
            $tagStmt->reset();

            // Link to user
            $linkStmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
            $linkStmt->bindValue(':tag', $tagName, SQLITE3_TEXT);
            $linkStmt->execute();
            $linkStmt->reset();
        }
    }

    // Commit everything
    $db->exec("COMMIT");

    // Optional: Set a success message
    $_SESSION['onboarding_complete'] = true;

    // Redirect based on role
    if ($role === 'gigster') {
        header("Location: ../pages/CreateGig.php");  // Let them create their first gig
    } else {
        header("Location: ../pages/BrowseGigs.php");  // Clients go straight to browsing
    }
    exit;

} catch (Exception $e) {
    $db->exec("ROLLBACK");
    die("Something went wrong. Please try again.");
}
?>