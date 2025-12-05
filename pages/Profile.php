<?php
session_start();
require_once __DIR__ . '/../database/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch gigster + tags
$stmt = $db->prepare("
    SELECT u.username, u.age, u.location, u.experience_years, u.is_pro,
           COALESCE(AVG(r.rating), 0) as avg_rating,
           COUNT(r.id) as review_count
    FROM users u
    LEFT JOIN gig_reviews r ON r.gig_id IN (SELECT id FROM gigs WHERE user_id = u.id)
    WHERE u.id = :id
    GROUP BY u.id
");
$stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
$result = $stmt->execute();
$gigster = $result->fetchArray(SQLITE3_ASSOC);

if (!$gigster) {
    die("Profile not found or you're not a gigster.");
}

// Fetch tags
$tags = [];
$tagResult = $db->query("
    SELECT t.name 
    FROM tags t
    JOIN user_tags ut ON ut.tag_id = t.id 
    WHERE ut.user_id = $userId
");
while ($row = $tagResult->fetchArray(SQLITE3_ASSOC)) {
    $tags[] = $row['name'];
}

// Fetch gigs
$gigs = [];
$gigResult = $db->query("
    SELECT g.id, g.title, g.description, g.price,
           COALESCE(AVG(r.rating), 0) as gig_rating,
           COUNT(r.id) as gig_reviews
    FROM gigs g
    LEFT JOIN gig_reviews r ON r.gig_id = g.id
    WHERE g.user_id = $userId AND g.status = 'active'
    GROUP BY g.id
    ORDER BY g.created_at DESC
");
while ($row = $gigResult->fetchArray(SQLITE3_ASSOC)) {
    $gigs[] = $row;
}

$avgRating = number_format($gigster['avg_rating'], 1);
$reviewCount = (int)$gigster['review_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/gig-details.css">
    <link rel="stylesheet" href="../css/rating.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/rating.css">

    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title><?= htmlspecialchars($gigster['username']) ?> • Gigsta Profile</title>
</head>

<style>
    .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.8);
}

.modal-content {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 80%;
    border-radius: 10px;
}

.close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}
</style>

<body>
    <?php include '../components/Header.php'; ?>

    <div class="gigster-profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <img src="../images/kirk.jpeg" alt="<?= htmlspecialchars($gigster['username']) ?>" class="profile-picture" id="profilePicture">

            <div class="profile-info">
                <h1>
                    <?= htmlspecialchars($gigster['username']) ?>
                    <?php if ($gigster['is_pro']): ?>
                        <span class="pro-badge">Gigsta Pro</span>
                    <?php endif; ?>
                </h1>

                <div class="rating-large">
                    <img src="../images/star-rating-icon.svg" alt="star" width="28">
                    <?= $avgRating ?> <span style="color:#666; font-weight:normal;">(<?= $reviewCount ?> reviews)</span>
                </div>

                <div class="meta-info">
                    <span>Age: <?= $gigster['age'] ?? '—' ?></span>
                    <span>Location: <?= htmlspecialchars($gigster['location'] ?? '—') ?></span>
                    <span>Experience: <?= $gigster['experience_years'] ?? '—' ?> years</span>
                </div>

                <?php if (!empty($tags)): ?>
                <div class="tags-display">
                    <?php foreach ($tags as $tag): ?>
                        <span class="tag <?= in_array($tag, ['Producer','Rapper']) ? strtolower($tag) : 'other' ?>">
                            <?= htmlspecialchars($tag) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Gigs Section -->
        <h2 class="section-title">My Gigs (<?= count($gigs) ?>)</h2>
        
        <?php if (empty($gigs)): ?>
            <p style="text-align:center; color:#999; font-size:1.2rem; padding:60px 0;">
                No gigs yet. <a href="CreateGig.php" style="color:#00cc88;">Create your first gig!</a>
            </p>
        <?php else: ?>
            <div class="gigs-grid">
                <?php foreach ($gigs as $gig): ?>
                    <a href="GigDetails.php?id=<?= $gig['id'] ?>" class="gig-card-profile" style="text-decoration:none; color:inherit;">
                        <div class="gig-image">
                            <img src="../images/gig-image-placeholder.jpg" alt="<?= htmlspecialchars($gig['title']) ?>">
                        </div>
                        <div class="gig-content">
                            <h3 class="gig-title-profile"><?= htmlspecialchars($gig['title']) ?></h3>
                            <p class="gig-desc"><?= htmlspecialchars(substr($gig['description'], 0, 120)) ?>...</p>
                            <div class="gig-footer-profile">
                                <div class="gig-rating-profile">
                                    <img src="../images/star-rating-icon.svg" alt="star" width="18">
                                    <?= number_format($gig['gig_rating'], 1) ?>
                                    <span style="color:#888; font-size:0.9rem;">(<?= $gig['gig_reviews'] ?>)</span>
                                </div>
                                <div class="gig-price-profile">
                                    From $<?= number_format($gig['price'], 2) ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Profile Picture Modal -->
    <div id="profileModal" class="modal" style="display:none;">
       <span class="close">&times;</span>
       <img class="modal-content" id="modalImage">
    </div>


    <script src="../js/header.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
    // ---- Theme logic ----
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);

    // ---- Profile picture modal logic ----
        const profilePic = document.getElementById('profilePicture');
        const modal = document.getElementById('profileModal');
        const modalImg = document.getElementById('modalImage');
        const span = document.querySelector('.close');

        if (profilePic && modal && modalImg && span) {
            profilePic.onclick = function() {
                modal.style.display = "block";
                modalImg.src = this.src;
            }

            span.onclick = function() { 
                modal.style.display = "none";
            }

            modal.onclick = function(e) {
               if (e.target === modal) modal.style.display = "none";
            }
        }
    });
    </script>

</body>
</html>