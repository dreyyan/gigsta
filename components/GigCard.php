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
 * - $ratingCount  : Number of ratings (e.g., 420)
 * - $price        : Price (e.g., 1000)
 */

// Ensure required variables are defined
$image       = $image ?? 'https://via.placeholder.com/250x200?text=Gig+Image';
$seller      = $seller ?? 'Unknown Seller';
$isPro       = $isPro ?? false;
$description = $description ?? 'No description provided.';
$ratingValue = $ratingValue ?? 0;
$ratingCount = $ratingCount ?? 0;
$price       = $price ?? '0';
?>

<div class="gig-card">
    <div class="gig-image">
        <img src="<?= htmlspecialchars($image) ?>">
    </div>
    <div class="gig-info">
        <div class="gig-header">
            <span class="gig-seller"><?= htmlspecialchars($seller) ?></span>
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
                <span class="rating-count">(<?= htmlspecialchars($ratingCount) ?>)</span>
            </div>
            <p class="gig-price">From $<?= htmlspecialchars($price) ?></p>
        </div>
    </div>
</div>