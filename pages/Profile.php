<?php
session_start();
require_once __DIR__ . '/../database/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /pages/Login.php");
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
    ? "/uploads/profiles/{$user['profile_pic']}"
    : "/image/temp.jpg";

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
    <link rel="stylesheet" href="/css/profile.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/dropdown.css">
    <link rel="stylesheet" href="/css/primary-button.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
</head>
<body>

<?php include __DIR__ . '/../components/Header.php'; ?>

<main class="main-profile">

    <!-- PROFILE CARD -->
    <div class="profile-card">
        <!-- Avatar -->
        <img class="profile-avatar" src="<?= $avatar ?>">

        <!-- Profile Content -->
        <div class="profile-info">

            <!-- Header -->
            <div class="profile-header">
                <div>
                    <h3 class="profile-name"><?= htmlspecialchars($username) ?></h3>
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
                <?php if ($age): ?><div><span>👤</span> <?= $age ?> years old</div><?php endif; ?>
                <div><span>📍</span> <?= htmlspecialchars($location) ?></div>
                <div><span>💼</span> <?= $experience ?> years</div>
            </div>

            <!-- Skills -->
            <?php if ($tags): ?>
            <div class="profile-skills">
                <?php foreach ($tags as $tag): ?>
                    <span class="skill-tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <button id="edit-profile-button" class="primary-btn">
                Edit Profile
            </button>
        </div>
    </div>

    <!-- GIGS SECTION -->
    <section class="gigs-section">
        <h2 class="section-title">My Gigs (<?= count($gigs) ?>)</h2>

        <div class="gigs-grid">
            <?php if (empty($gigs)): ?>
                <p>No gigs yet. <a id="create-gig-link" href="/pages/CreateGig.php">Create your first gig!</a></p>
            <?php else: ?>
                <?php foreach ($gigs as $gig): ?>
                    <div class="gig-card">
                        <img class="gig-image" src="/image/temp.jpg" alt="Gig">

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
<script src="/js/dropdown.js"></script>
<!-- EDIT PROFILE MODAL (invisible until clicked) -->
<div id="editModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); backdrop-filter:blur(8px); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:white; padding:40px; border-radius:20px; width:90%; max-width:500px; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <h3 style="margin:0; font-family:'DM Sans'; font-size:26px;">Edit Profile</h3>
            <button onclick="document.getElementById('editModal').style.display='none'" style="background:none; border:none; font-size:32px; cursor:pointer; color:#999;">×</button>
        </div>

        <form id="editProfileForm">
            <div style="margin-bottom:16px;">
                <label style="display:block; margin-bottom:8px; font-weight:600;">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required style="width:100%; padding:12px 16px; border:2px solid #ddd; border-radius:12px; font-size:16px;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; margin-bottom:8px; font-weight:600;">Location</label>
                <input type="text" name="location" value="<?= htmlspecialchars($location) ?>" style="width:100%; padding:12px 16px; border:2px solid #ddd; border-radius:12px; font-size:16px;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; margin-bottom:8px; font-weight:600;">Age</label>
                <input type="number" name="age" value="<?= $age ?: '' ?>" min="13" max="100" style="width:100%; padding:12px 16px; border:2px solid #ddd; border-radius:12px; font-size:16px;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; margin-bottom:8px; font-weight:600;">Years of Experience</label>
                <input type="number" name="experience" value="<?= $experience ?>" min="0" style="width:100%; padding:12px 16px; border:2px solid #ddd; border-radius:12px; font-size:16px;">
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block; margin-bottom:8px; font-weight:600;">Skills (comma separated)</label>
                <input type="text" name="tags" value="<?= htmlspecialchars(implode(', ', $tags)) ?>" placeholder="e.g. Photoshop, Figma, React" style="width:100%; padding:12px 16px; border:2px solid #ddd; border-radius:12px; font-size:16px;">
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" style="flex:1; padding:14px; background:#6c5ce7; color:white; border:none; border-radius:12px; font-weight:700; cursor:pointer; font-size:16px;">
                    Save Changes
                </button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" style="padding:14px 24px; background:#f0f0f0; border:none; border-radius:12px; cursor:pointer; font-size:16px;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Open modal
document.getElementById('edit-profile-button')?.addEventListener('click', () => {
    document.getElementById('editModal').style.display = 'flex';
});

// Close when clicking outside
window.addEventListener('click', (e) => {
    const modal = document.getElementById('editModal');
    if (e.target === modal) modal.style.display = 'none';
});

// Submit form
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('../database/update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Profile updated!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('Connection failed'));
});
</script>
</body>
</html>