<?php
// database/gig_reviews.php
require_once __DIR__ . '/connection.php';

// Create gig_reviews table
$db->exec("
    CREATE TABLE IF NOT EXISTS gig_reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        gig_id INTEGER NOT NULL,
        client_id INTEGER NOT NULL,
        rating REAL CHECK(rating >= 1 AND rating <= 5),
        review TEXT,
        created_at TEXT DEFAULT (datetime('now')),
        FOREIGN KEY(gig_id) REFERENCES gigs(id),
        FOREIGN KEY(client_id) REFERENCES users(id)
    )
");

// Get average rating and review count for a gig
function getGigRating($gigId, $db) {
    $stmt = $db->prepare("
        SELECT 
            AVG(rating) as avg_rating, 
            COUNT(*) as review_count
        FROM gig_reviews 
        WHERE gig_id = :gig_id
    ");
    $stmt->bindValue(':gig_id', $gigId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    return [
        'avg_rating' => round($row['avg_rating'] ?? 0, 1),
        'review_count' => (int)($row['review_count'] ?? 0)
    ];
}
?>