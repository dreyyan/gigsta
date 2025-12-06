<?php
session_start();
require_once __DIR__ . '/../database/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /gigsta/pages/Login.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];

// Fetch user safely
$user = $db->querySingle("SELECT * FROM users WHERE id = $userId", true);
if (!$user) die("User not found.");

// Safe data
$username     = $user['username'] ?? 'Guest';
$age          = $user['age'] ?? null;
$location     = $user['location'] ?? 'Unknown';
$experience   = (int)($user['experience_years'] ?? 0);
$isPro        = !empty($user['is_pro']);
$tags         = !empty($user['tags']) ? json_decode($user['tags'], true) : [];
$tags         = is_array($tags) ? $tags : [];

// Avatar
$avatar = (!empty($user['profile_pic']) && file_exists("../uploads/profiles/{$user['profile_pic']}"))
    ? "/gigsta/uploads/profiles/{$user['profile_pic']}"
    : "/gigsta/image/temp.jpg";

// Rating & reviews
$stats = $db->querySingle("
    SELECT COALESCE(AVG(r.rating), 0) as avg_rating, COUNT(r.id) as review_count
    FROM gigs g
    LEFT JOIN gig_reviews r ON r.gig_id = g.id
    WHERE g.user_id = $userId
", true);
$avgRating = round($stats['avg_rating'] ?? 0, 1);
$reviewCount = $stats['review_count'] ?? 0;

// Gigs
$gigs = [];
$result = $db->query("SELECT id, title, price FROM gigs WHERE user_id = $userId ORDER BY created_at DESC");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $gigs[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($username) ?> • Gigsta Profile</title>
    <link rel="stylesheet" href="/gigsta/css/profile.css">
    <link rel="stylesheet" href="/gigsta/css/header.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
</head>
<body>

<?php include __DIR__ . '/../components/Header.php'; ?>

<main class="main-profile">

    <!-- PROFILE CARD -->
    <div class="profile-card">

        <!-- Avatar -->
        <img class="profile-avatar" src="<?= $avatar ?>" alt="<?= htmlspecialchars($username) ?>">

        <!-- Profile Content -->
        <div class="profile-info">

            <!-- Header -->
            <div class="profile-header">
                <div>
                    <h2 class="profile-name"><?= htmlspecialchars($username) ?></h2>
                    <div class="profile-rating">
                        ⭐ <?= $avgRating ?> <span>(<?= $reviewCount ?>)</span>
                    </div>
                </div>

                <?php if ($isPro): ?>
                    <button class="pro-btn">Gigsta Pro</button>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <p class="profile-bio">
                <?= $experience ?> years of experience from <?= htmlspecialchars($location) ?>. 
                Specializing in <?= $tags ? implode(', ', array_slice($tags, 0, 4)) : 'freelancing' ?>. 
                Professional and reliable service with fast delivery.
            </p>

            <!-- Details -->
            <div class="profile-details">
                <?php if ($age): ?><div><span>Age</span> <?= $age ?> years old</div><?php endif; ?>
                <div><span>Location</span> <?= htmlspecialchars($location) ?></div>
                <div><span>Experience</span> <?= $experience ?> years</div>
            </div>

            <!-- Skills -->
            <?php if ($tags): ?>
            <div class="profile-skills">
                <?php foreach ($tags as $tag): ?>
                    <span class="skill-tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- GIGS SECTION -->
    <section class="gigs-section">
        <h2 class="section-title">My Gigs (<?= count($gigs) ?>)</h2>

        <div class="gigs-grid">
            <?php if (empty($gigs)): ?>
                <p>No gigs yet. <a href="/gigsta/pages/CreateGig.php">Create your first gig!</a></p>
            <?php else: ?>
                <?php foreach ($gigs as $gig): ?>
                    <div class="gig-card">
                        <img class="gig-image" src="/gigsta/image/temp.jpg" alt="Gig">

                        <div class="gig-info">
                            <div class="gig-user">
                                <img src="<?= $avatar ?>" class="gig-avatar" alt="<?= htmlspecialchars($username) ?>">
                                <span><?= htmlspecialchars($username) ?></span>
                            </div>

                            <p><?= htmlspecialchars($gig['title']) ?></p>

                            <div class="gig-meta">
                                <span>⭐ <?= $avgRating ?> (<?= $reviewCount ?>)</span>
                                <strong>$<?= number_format($gig['price'], 2) ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

</main>
<script src="/gigsta/js/dropdown.js"></script>
</body>
</html>