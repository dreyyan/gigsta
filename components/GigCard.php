<?php
/**
 * GigCard.php
 * Dynamic gig card component
 *
 * Parameters:
 * - $image        : URL of gig image
 * - $seller       : Seller name
 * - $isPro        : true/false for Gigsta Pro badge
 * - $description  : Short gig description
 * - $ratingValue  : Rating value (e.g., 5.0)
 * - $ratingCount  : Number of reviews (e.g., 420)
 * - $price        : Price (e.g., 1000)
 */

// Ensure required variables are defined
$image       = $image ?? '../images/gig-image-placeholder.jpg';
$seller      = $seller ?? 'Unknown Seller';
$isPro       = $isPro ?? false;
$description = $description ?? 'No description provided.';
$ratingValue = $ratingValue ?? 0; // Average rating
$ratingCount = $ratingCount ?? 0; // Number of reviews
$price       = $price ?? '0';
?>

<div class="gig-card">
    <div class="gig-image">
        <img src="<?= htmlspecialchars($image) ?>" alt="Gig Image">
    </div>
    <div class="gig-info">
        <div class="gig-header">
            <div id="gig-header-left-container">
                <img id="gig-header-profile-icon" src="../images/profile-placeholder-icon.svg" alt="Profile Placeholder">
                <span class="gig-seller"><?= htmlspecialchars($seller) ?></span>
            </div>
            <?php if ($isPro): ?>
                <span class="pro-badge">
                    <img src="../images/thunder-badge-icon.svg" alt="pro" class="badge-icon">
                    Gigsta Pro
                </span>
            <?php endif; ?>
        </div>

        <p class="gig-description"><?= htmlspecialchars($description) ?></p>

        <div class="gig-footer">
            <div class="rating">
                <img src="../images/star-rating-icon.svg" alt="star" class="rating-star">
                <span class="rating-value"><?= htmlspecialchars($ratingValue) ?></span>
                <span class="rating-count">(<?= htmlspecialchars($ratingCount) ?> reviews)</span>
            </div>
            <p class="gig-price">From $<?= htmlspecialchars($price) ?></p>
        </div>
    </div>
</div>