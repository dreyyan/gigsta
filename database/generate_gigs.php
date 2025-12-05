<?php
require_once __DIR__ . '/../database/connection.php';

echo "<pre style='font-family: monospace; background:#000; color:#0f0; padding:30px; font-size:15px;'>";
echo "GENERATING 50 GIGS + REAL REVIEWS & RATINGS...\n\n";

$categories = ['Graphics & Design', 'Digital Marketing', 'Writing & Translation', 'Video & Animation', 'Music & Audio', 'Programming & Tech', 'Business'];
$titles = ['Professional Logo Design', 'Modern Website Development', 'SEO Optimization', 'Social Media Management', 'Video Editing & Animation', 'Content Writing', 'WordPress Setup', 'Mobile App UI Design', 'Voice Over Recording', 'Brand Identity Package', 'YouTube Thumbnail Design', 'Flyer Design', 'Photo Retouching', 'Translation Services', 'App Development', 'Custom Illustration', 'Podcast Editing', '3D Animation', 'Explainer Video'];
$descriptions = ['I will deliver high-quality work with unlimited revisions until you are 100% satisfied.', 'Fast delivery, professional results, excellent communication throughout.', 'Premium quality service at an affordable price. Let’s bring your idea to life!', 'Your satisfaction is my top priority. Professional and reliable service.', 'Expert-level work with years of experience. Quick turnaround guaranteed.'];
$reviewTexts = [
    "Amazing work! Delivered super fast.", "Highly recommend, very professional!", "Exactly what I needed. Thank you!", 
    "Great communication and quality.", "Will hire again for sure!", "Perfect! Better than expected.", 
    "Outstanding service!", "Super talented freelancer!", "Fast and flawless delivery.", "Top-notch quality!"
];

// Get all gigster IDs
$gigsterResult = $db->query("SELECT id FROM users WHERE role = 'gigster'");
$gigsterIds = [];
while ($row = $gigsterResult->fetchArray(SQLITE3_ASSOC)) {
    $gigsterIds[] = $row['id'];
}

if (empty($gigsterIds)) {
    die("No gigsters found! Run full_setup.php first.\n");
}

// Get some client IDs for fake reviews (or fallback to gigster IDs)
$clientResult = $db->query("SELECT id FROM users WHERE role = 'client' LIMIT 10");
$clientIds = [];
while ($row = $clientResult->fetchArray(SQLITE3_ASSOC)) {
    $clientIds[] = $row['id'];
}
if (empty($clientIds)) $clientIds = $gigsterIds; // fallback

$totalGigs = 50;
$gigsCreated = 0;

while ($gigsCreated < $totalGigs) {
    $userId = $gigsterIds[array_rand($gigsterIds)];
    $title = $titles[array_rand($titles)];
    $description = $descriptions[array_rand($descriptions)];
    $category = $categories[array_rand($categories)];
    $price = random_int(10, 300) + round(random_int(0, 99) / 100, 2);

    // Insert gig
    $stmt = $db->prepare("
        INSERT INTO gigs (user_id, title, description, category, price, status, created_at)
        VALUES (:user_id, :title, :desc, :category, :price, 'active', datetime('now'))
    ");
    $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);
    $stmt->bindValue(':desc', $description, SQLITE3_TEXT);
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->execute();

    $gigId = $db->lastInsertRowID();

    // Add 1–7 fake reviews per gig
    $numReviews = random_int(1, 7);
    for ($i = 0; $i < $numReviews; $i++) {
        $clientId = $clientIds[array_rand($clientIds)];
        $rating = random_int(40, 50) / 10; // 4.0 to 5.0
        $review = $reviewTexts[array_rand($reviewTexts)];

        $reviewStmt = $db->prepare("
            INSERT INTO gig_reviews (gig_id, client_id, rating, review, created_at)
            VALUES (?, ?, ?, ?, datetime('now'))
        ");
        $reviewStmt->bindValue(1, $gigId, SQLITE3_INTEGER);
        $reviewStmt->bindValue(2, $clientId, SQLITE3_INTEGER);
        $reviewStmt->bindValue(3, $rating, SQLITE3_FLOAT);
        $reviewStmt->bindValue(4, $review, SQLITE3_TEXT);
        $reviewStmt->execute();
    }

    $gigsCreated++;
    echo "Gig #$gigsCreated → \"$title\" ($rating stars, $numReviews reviews) → user $userId\n";
}

echo "\nSUCCESS! 50 gigs created with real reviews and ratings!\n";
echo "Go to BrowseGigs.php — you will now see stars and review counts!\n";
echo "Safe to delete this file.\n";
echo "</pre>";
?>