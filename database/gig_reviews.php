<?php
// database/gig_reviews.php
// FINAL VERSION – NO TIMEOUT, NO HANGING, WORKS INSTANTLY

// Only connect if $db is not already available
if (!isset($db) || !($db instanceof SQLite3)) {
    require_once __DIR__ . '/connection.php';
}

// Create table (safe, runs only once)
$db->exec("
    CREATE TABLE IF NOT EXISTS gig_reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        gig_id INTEGER NOT NULL,
        client_id INTEGER NOT NULL,
        rating REAL CHECK(rating >= 1 AND rating <= 5),
        review TEXT,
        created_at TEXT DEFAULT (datetime('now')),
        FOREIGN KEY(gig_id) REFERENCES gigs(id) ON DELETE CASCADE,
        FOREIGN KEY(client_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

// THE ONLY VERSION THAT NEVER HANGS
function getGigRating(int $gigId): array
{
    global $db;  // Use the global $db from connection.php

    // Default return (no reviews)
    $default = [
        'avg_rating'   => 0.0,
        'review_count' => 0
    ];

    if ($gigId <= 0) {
        return $default;
    }

    // Fast, single-row query with COALESCE
    $sql = "SELECT 
                COALESCE(ROUND(AVG(rating), 1), 0.0) AS avg_rating,
                COUNT(*) AS review_count
            FROM gig_reviews 
            WHERE gig_id = ?";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return $default;
    }

    $stmt->bindValue(1, $gigId, SQLITE3_INTEGER);

    $result = $stmt->execute();
    if (!$result) {
        $stmt->close();
        return $default;
    }

    $row = $result->fetchArray(SQLITE3_ASSOC);

    // CRITICAL: Clean up immediately
    $result->finalize();
    $stmt->close();

    return $row ? [
        'avg_rating'   => (float)$row['avg_rating'],
        'review_count' => (int)$row['review_count']
    ] : $default;
}
?>