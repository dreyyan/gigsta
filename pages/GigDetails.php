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
    <link rel="stylesheet" href="../css/gig-details.css">
    <link rel="stylesheet" href="../css/dropdown.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/rating.css">
    <link rel="stylesheet" href="../css/primary-button.css">
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

            <div class="gig-rating-large">
                <h2 class="section-title">Reviews</h2>
                <div id="section-reviews-container">
                    <img src="../images/star-rating-icon.svg" alt="star" width="28">
                    <span class="rating-value"><?= $avgRating ?></span>
                    <span class="review-count">(<?= $reviewCount ?> <?= $reviewCount == 1 ? 'review' : 'reviews' ?>)</span>
                </div>
            </div>
            <div class="reviews-list">
                <?php
                $reviewStmt = $db->prepare("
                    SELECT gr.rating, gr.review, gr.created_at, u.username 
                    FROM gig_reviews gr
                    JOIN users u ON gr.client_id = u.id
                    WHERE gr.gig_id = :gig_id
                    ORDER BY gr.created_at DESC
                ");
                $reviewStmt->bindValue(':gig_id', $gigId, SQLITE3_INTEGER);
                $reviewResult = $reviewStmt->execute();

                if ($reviewCount == 0): ?>
                    <p style="color:#888; font-style:italic; padding:20px 0;">No reviews yet. Be the first!</p>
                <?php else: ?>
                    <?php while ($review = $reviewResult->fetchArray(SQLITE3_ASSOC)): ?>
                        <div style="background:#f9f9f9; padding:20px; border-radius:12px; margin-bottom:16px;">
                            <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                                <img src="../images/profile-placeholder-icon.svg" width="40" style="border-radius:50%;">
                                <div>
                                    <strong><?= htmlspecialchars($review['username']) ?></strong>
                                    <div style="margin-top:4px;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span style="color:<?= $i <= $review['rating'] ? '#ffb400' : '#ddd' ?>; font-size:1.2em;">★</span>
                                        <?php endfor; ?>
                                        <span style="margin-left:8px; color:#666; font-size:0.9em;">
                                            <?= number_format($review['rating'], 1) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p style="margin:12px 0; line-height:1.6; color:#333;">
                                <?= htmlspecialchars($review['review']) ?>
                            </p>
                            <small style="color:#999;">
                                <?= date('M j, Y \• g:i A', strtotime($review['created_at'])) ?>
                            </small>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="seller-card">
                <h4>Gigster's Profile</h4>
                <img src="../images/gig-details-sidebar-profile.svg" alt="Seller">
                <h5><?= htmlspecialchars($gig['username']) ?></h5>
                <div class="seller-rating">
                    ★ <?= $avgRating ?> <span style="color:#888; font-size:0.9em;">(<?= $reviewCount ?>)</span>
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