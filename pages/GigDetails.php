<?php
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../database/gig_reviews.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid gig ID");
}

$gigId = (int)$_GET['id'];

$stmt = $db->prepare("
    SELECT gigs.*, users.username, users.is_pro 
    FROM gigs 
    INNER JOIN users ON gigs.user_id = users.id 
    WHERE gigs.id = :id
");
$stmt->bindValue(':id', $gigId, SQLITE3_INTEGER);
$result = $stmt->execute();
$gig = $result->fetchArray(SQLITE3_ASSOC);

if (!$gig) die("Gig not found");

$ratingData   = getGigRating($gigId, $db);
$avgRating    = $ratingData['avg_rating'] > 0 ? number_format($ratingData['avg_rating'], 1) : '0.0';
$reviewCount  = $ratingData['review_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/gig-details.css">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title><?= htmlspecialchars($gig['title']) ?> • Gigsta</title>
</head>
<body>
    <?php include '../components/Header.php'; ?>

    <div class="gig-details-page">
        <!-- MAIN CONTENT -->
        <div class="main-content">

            <!-- IMAGE + THUMBNAILS (SIDE BY SIDE) -->
            <div class="image-gallery">
                <div class="main-image">
                    <img src="../images/gig-image-placeholder.jpg" alt="<?= htmlspecialchars($gig['title']) ?>">
                </div>
                <div class="thumbnails">
                    <img src="../images/gig-image-placeholder.jpg" alt="thumb">
                    <img src="../images/gig-image-placeholder.jpg" alt="thumb">
                    <img src="../images/gig-image-placeholder.jpg" alt="thumb">
                    <img src="../images/gig-image-placeholder.jpg" alt="thumb">
                </div>
            </div>

            <!-- SELLER INFO + RATING + PRICE (under name) -->
            <div class="seller-header">
                <img src="../images/profile-placeholder-icon.svg" alt="Seller" class="seller-avatar">
                <div class="seller-info">
                    <div class="seller-name">
                        <?= htmlspecialchars($gig['username']) ?>
                        <?php if ($gig['is_pro']): ?>
                    <span class="pro-badge">
                        <img src="../images/thunder-badge-icon.svg" alt="pro" class="badge-icon">
                        Gigsta Pro
                    </span>
                        <?php endif; ?>
                    </div>

                    <div class="gig-footer">
                        <div class="rating">
                            <img src="../images/star-rating-icon.svg" alt="star" class="rating-star">
                            <span class="rating-value"><?= $avgRating ?></span>
                            <span class="rating-count">(<?= $reviewCount ?> <?= $reviewCount == 1 ? 'review' : 'reviews' ?>)</span>
                        </div>
                        <div id="price-container">
                            <p id="from-text">From </p>
                            <p class="gig-price">$<?= number_format($gig['price'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <h1 class="gig-title"><?= htmlspecialchars($gig['title']) ?></h1>

            <div class="description">
                <p><?= nl2br(htmlspecialchars($gig['description'] ?? 'No description provided.')) ?></p>
            </div>

            <h2 class="section-title">Reviews</h2>
            <div class="reviews-grid">
                <p>No reviews yet. Be the first!</p>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="seller-card">
                <h4>Gigster's Profile</h4>
                <img src="../images/gig-details-sidebar-profile.svg" alt="Seller">
                <h5><?= htmlspecialchars($gig['username']) ?></h5>
                <div class="seller-rating">
                    ★ <?= $avgRating ?>
                    <p id="review-count">(<?= $reviewCount ?>)</p>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing...</p>
                <div class="seller-meta">
                    <div>👤 40 years old</div>
                    <div>📍 Berlin, Germany</div>
                    <div>💼 5 Years on Gigsta</div>
                </div>
                <div class="seller-tags">
                    <span class="tag producer">Producer</span>
                    <span class="tag rapper">Rapper</span>
                    <span class="tag other">Programmer</span>
                    <span class="tag other">Videographer</span>
                </div>

                <?php 
                    $label = "Contact"; 
                    $id = "contact-btn";
                    $icon = "../images/contact-icon.svg";
                    $href  = "Chats.php?with=" . $gig['user_id'];
                    include '../components/PrimaryButton.php'; 
                ?>
            </div>
        </div>
    </div>

    <script src="../js/dropdown.js"></script>
</body>
</html>