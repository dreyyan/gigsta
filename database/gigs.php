<?php
// database/gigs.php
require_once __DIR__ . '/connection.php';

// Create gigs table
$db->exec("
    CREATE TABLE IF NOT EXISTS gigs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        description TEXT,
        category TEXT,
        price REAL DEFAULT 0,
        status TEXT DEFAULT 'active',
        created_at TEXT DEFAULT (datetime('now')),
        FOREIGN KEY(user_id) REFERENCES users(id)
    )
");

// Fetch gigs for a user
function getUserGigs($userId, $db) {
    $stmt = $db->prepare("SELECT * FROM gigs WHERE user_id = :user_id");
    $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $gigs = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $gigs[] = $row;
    }
    return $gigs;
}

// Fetch all gigs (with optional category filter)
function getGigs($db, $category = null) {
    $sql = "SELECT * FROM gigs";
    if ($category) $sql .= " WHERE category = :category";
    $stmt = $db->prepare($sql);
    if ($category) $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $result = $stmt->execute();

    $gigs = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $gigs[] = $row;
    }
    return $gigs;
}
?>