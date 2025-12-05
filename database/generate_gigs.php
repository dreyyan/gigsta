<?php
require_once __DIR__ . '/db.php';

// Categories
$categories = [
    'Graphics & Design',
    'Digital Marketing',
    'Writing & Translation',
    'Video & Animation',
    'Music & Audio',
    'Programming & Tech'
];

// Sample titles and descriptions
$titles = [
    'Professional Logo Design',
    'Website Development',
    'SEO Optimization',
    'Social Media Management',
    'Video Editing & Animation',
    'Custom Illustration',
    'Voice Over Recording',
    'Content Writing & Blogging',
    'App Development',
    'Brand Strategy Consultation'
];

$descriptions = [
    'I will create a professional and unique work tailored for you.',
    'High-quality service delivered on time.',
    'Your satisfaction is my top priority.',
    'I bring creativity and experience to every project.',
    'Affordable and reliable services for all clients.',
    'Expert work with attention to detail.',
    'Professional results with unlimited revisions.',
    'I provide fast turnaround without compromising quality.'
];

// Fetch all gigsters
$result = $db->query("SELECT id, username FROM users WHERE role='gigster'");
$gigsters = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $gigsters[] = $row;
}

foreach ($gigsters as $gigster) {
    for ($i = 0; $i < 20; $i++) {
        $title = $titles[array_rand($titles)];
        $description = $descriptions[array_rand($descriptions)];
        $category = $categories[array_rand($categories)];
        $price = rand(10, 500);
        $rating = rand(10, 50) / 10; // 1.0 to 5.0
        $reviews = rand(0, 500);

        $stmt = $db->prepare("INSERT INTO gigs (user_id, title, description, category, price, status, created_at) 
                              VALUES (:user_id, :title, :description, :category, :price, 'active', datetime('now'))");
        $stmt->bindValue(':user_id', $gigster['id'], SQLITE3_INTEGER);
        $stmt->bindValue(':title', $title, SQLITE3_TEXT);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':category', $category, SQLITE3_TEXT);
        $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
        $stmt->execute();

        // Optionally store rating and review count in a separate table if needed
        $gig_id = $db->lastInsertRowID();
        $db->exec("CREATE TABLE IF NOT EXISTS gig_reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            gig_id INTEGER NOT NULL,
            rating REAL,
            reviews INTEGER,
            FOREIGN KEY(gig_id) REFERENCES gigs(id)
        )");
        $db->exec("INSERT INTO gig_reviews (gig_id, rating, reviews) VALUES ($gig_id, $rating, $reviews)");
    }
}

echo "Gigs generated successfully!";
?>