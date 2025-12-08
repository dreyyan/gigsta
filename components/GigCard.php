<?php
/**
 * GigCard.php — Clickable version (CSS-safe)
 */
$image       = $image       ?? '../images/gig-image-placeholder.jpg';
$seller      = $seller      ?? 'Unknown Seller';
$isPro       = $isPro       ?? false;
$description = $description ?? 'No description provided.';
$ratingValue = $ratingValue ?? 0;
$ratingCount = $ratingCount ?? 0;
$price       = $price       ?? '0';

// THIS IS THE ONLY NEW REQUIRED VARIABLE
$gigId = $gigId ?? 0;
?>

<a href="/pages/GigDetails.php?id=<?= (int)$gigId ?>" class="gig-card-link">
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
                    <img src="/images/star-rating-icon.svg" alt="star" class="rating-star">
                    <span class="rating-value"><?= htmlspecialchars($ratingValue) ?></span>
                    <span class="rating-count">
                        (<?= htmlspecialchars($ratingCount) ?> <?= $ratingCount == 1 ? 'review' : 'reviews' ?>)
                    </span>
                </div>
                <div id="price-container">
                    <p id="from-text">From </p>
                    <p class="gig-price">$<?= htmlspecialchars($price) ?></p>
                </div>
            </div>
        </div>
    </div>
</a>