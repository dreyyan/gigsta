<?php
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../database/gig_reviews.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid gig ID");
}

$gigId = (int)$_GET['id'];

// Fetch gig + seller info
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

// Rating
$ratingData = getGigRating($gigId, $db);
$avgRating = $ratingData['avg_rating'] > 0 ? number_format($ratingData['avg_rating'], 1) : '0.0';
$reviewCount = $ratingData['review_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title><?= htmlspecialchars($gig['title']) ?> • Gigsta</title>

    <style>
        .gig-details-page {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 48px;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 48px;
        }

        .main-content { grid-column: 1; }
        .sidebar { grid-column: 2; position: sticky; top: 100px; align-self: start; }

        /* Mobile: stack everything */
        @media (max-width: 1024px) {
            .gig-details-page {
                grid-template-columns: 1fr;
                padding: 24px 16px;
                gap: 32px;
            }
            .sidebar { position: static; order: -1; }
        }

        /* Gig Header */
        .gig-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }
        .gig-header img { width: 60px; height: 60px; border-radius: 50%; }
        .seller-name { font-weight: 600; font-size: 1.4rem; }
        .pro-badge { background: #000; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; }

        .gig-title { font-size: 2.4rem; font-weight: 700; margin: 0 0 24px; line-height: 1.2; }
        .gig-price { font-size: 2rem; font-weight: 700; color: #00cc88; margin: 24px 0; }
        .contact-btn {
            background: #000;
            color: white;
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .contact-btn:hover { background: #222; }

        .gig-image-main img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .gallery-thumbs {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }
        .gallery-thumbs img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 3px solid transparent;
            cursor: pointer;
        }
        .gallery-thumbs img:hover { border-color: #00cc88; }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 48px 0 24px;
        }

        .description {
            line-height: 1.8;
            font-size: 1.1rem;
            color: #333;
        }

        /* Sidebar Seller Card */
        .seller-card {
            background: #f9f9f9;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
        }
        .seller-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 16px;
        }
        .seller-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin: 16px 0;
        }
        .tag {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .tag.producer { background: #ff4d4d; color: white; }
        .tag.rapper { background: #ffbf00; color: black; }
        .tag.other { background: #e0e0e0; color: #333; }

        /* Reviews Grid */
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        .review-card {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 12px;
        }
        .review-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .review-header img { width: 40px; height: 40px; border-radius: 50%; }
        .stars { color: #ffb400; }

        /* Similar Gigs */
        .similar-gigs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }
    </style>
</head>
<body>
    <?php include '../components/Header.php'; ?>

    <div class="gig-details-page">
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- Gig Image -->
            <div class="gig-image-main">
                <img src="../images/gig-image-placeholder.jpg" alt="<?= htmlspecialchars($gig['title']) ?>">
            </div>

            <!-- Gallery Thumbnails -->
            <div class="gallery-thumbs">
                <img src="../images/gig-image-placeholder.jpg" alt="thumb">
                <img src="../images/gig-image-placeholder.jpg" alt="thumb">
                <img src="../images/gig-image-placeholder.jpg" alt="thumb">
                <img src="../images/gig-image-placeholder.jpg" alt="thumb">
            </div>

            <!-- Gig Info -->
            <div class="gig-header">
                <img src="../images/profile-placeholder-icon.svg" alt="Seller">
                <div>
                    <div class="seller-name"><?= htmlspecialchars($gig['username']) ?></div>
                    <?php if ($gig['is_pro']): ?>
                        <span class="pro-badge">GigstaPro</span>
                    <?php endif; ?>
                </div>
            </div>

            <h1 class="gig-title"><?= htmlspecialchars($gig['title']) ?></h1>

            <div class="description">
                <p><?= nl2br(htmlspecialchars($gig['description'] ?? 'No description provided.')) ?></p>
            </div>

            <h2 class="section-title">Reviews</h2>
            <div class="reviews-grid">
                <!-- Example review cards -->
                <div class="review-card">
                    <div class="review-header">
                        <img src="../images/profile-placeholder-icon.svg" alt="Reviewer">
                        <div>
                            <strong>John Doe</strong><br>
                            <small>@johndoe_01</small>
                        </div>
                        <div class="stars">5 stars</div>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                </div>
                <!-- Repeat as needed -->
            </div>

            <h2 class="section-title">Similar Gigs</h2>
            <div class="similar-gigs-grid">
                <!-- Reuse your GigCard.php here later -->
                <div style="background:#f0f0f0; height:300px; border-radius:12px;"></div>
                <div style="background:#f0f0f0; height:300px; border-radius:12px;"></div>
                <div style="background:#f0f0f0; height:300px; border-radius:12px;"></div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div style="margin-bottom: 32px;">
                <div class="gig-price">From $<?= number_format($gig['price'], 2) ?></div>
                <button class="contact-btn">Contact</button>
            </div>

            <div class="seller-card">
                <img src="../images/profile-placeholder-icon.svg" alt="Seller">
                <h3><?= htmlspecialchars($gig['username']) ?></h3>
                <div style="color:#ffb400; font-size:1.2rem; margin:8px 0;">
                    5 stars (<?= $reviewCount ?>)
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor...</p>

                <div style="margin:20px 0; color:#666;">
                    <div>40 years old</div>
                    <div>Berlin, Germany</div>
                    <div>5 Years</div>
                </div>

                <div class="seller-tags">
                    <span class="tag producer">Producer</span>
                    <span class="tag rapper">Rapper</span>
                    <span class="tag other">Programmer</span>
                    <span class="tag other">Videographer</span>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/dropdown.js"></script>
</body>
</html>