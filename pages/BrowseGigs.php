<?php
// DEBUG: Remove these 4 lines in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../database/gig_reviews.php';

// GET parameters
$query    = trim($_GET['query'] ?? '');
$budget   = $_GET['budget'] ?? 'Any';
$sort     = $_GET['sort'] ?? 'newest';
$delivery = $_GET['delivery'] ?? 'Any';

// ------------------------------------------------------------------
// Build the SQL query
// ------------------------------------------------------------------
$sql = "SELECT gigs.*, users.username, users.role, users.is_pro
        FROM gigs
        INNER JOIN users ON gigs.user_id = users.id
        WHERE users.role = 'gigster' AND gigs.status = 'active'";

$params = [];

// Search filter
if ($query !== '') {
    $sql .= " AND (gigs.title LIKE :q OR gigs.description LIKE :q OR users.username LIKE :q)";
    $params[':q'] = '%' . $query . '%';
}

// Budget filter
if ($budget !== 'Any') {
    switch ($budget) {
        case 'Under $50':
            $sql .= " AND gigs.price < 50";
            break;
        case '$50 - $100':
            $sql .= " AND gigs.price BETWEEN 50 AND 100";
            break;
        case '$100+':
            $sql .= " AND gigs.price > 100";
            break;
    }
}

// Delivery Time filter - EXACT MATCH with database values
if ($delivery !== 'Any') {
    switch ($delivery) {
        case '24 hours':
        case '3 days':
        case '7 days':
        case '10 days':
        case '14 days':
            $sql .= " AND gigs.delivery_time = :delivery";
            $params[':delivery'] = $delivery;
            break;
        default:
            // Fallback: if someone types weird value, ignore
            $delivery = 'Any';
            break;
    }
}

// Sorting
switch ($sort) {
    case 'price_low':
        $sql .= " ORDER BY gigs.price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY gigs.price DESC";
        break;
    case 'best_selling':
        // Placeholder - you can improve later with order count
        $sql .= " ORDER BY gigs.created_at DESC";
        break;
    case 'newest':
    default:
        $sql .= " ORDER BY gigs.created_at DESC";
        break;
}

// ------------------------------------------------------------------
// Execute query
// ------------------------------------------------------------------
$stmt = $db->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, SQLITE3_TEXT);
}
$result = $stmt->execute();

$filtered = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $ratingData = getGigRating($row['id'], $db);
    $row['avg_rating']    = $ratingData['avg_rating'] ?? 0.0;
    $row['review_count']  = $ratingData['review_count'] ?? 0;
    $filtered[] = $row;
}

// ------------------------------------------------------------------
// Dropdown helpers
// ------------------------------------------------------------------
$budgetOptions = ['Any', 'Under $50', '$50 - $100', '$100+'];
$currentSortText = match ($sort) {
    'price_low'     => 'Price: Low to High',
    'price_high'    => 'Price: High to Low',
    'best_selling'  => 'Best selling',
    default         => 'Newest'
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/primary-button.css">
    <link rel="stylesheet" href="../css/dropdown.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/gig-card.css">
    <style>
@media (max-width: 600px) {
    h2 {
        font-size: 22px;
    }

    .search-results-container {
        padding: 0 16px;
    }

    .results-title p,
    .results-count p {
        font-size: 14px;
    }
}
</style>

    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta • Find Freelancers</title>
</head>
<body>
    <?php include '../components/Header.php'; ?>

    <main class="search-results-container">
        <h2>Browse Gigs</h2>

        <div class="filters-section">
            <div class="filter-buttons">

                <!-- BUDGET DROPDOWN -->
                <?php
                $label = "Budget";
                $isFilter = true;
                $q = $query ? '&query=' . urlencode($query) : '';
                $d = $delivery !== 'Any' ? '&delivery=' . urlencode($delivery) : '';
                $s = $sort !== 'newest' ? '&sort=' . $sort : '';

                $items = [];
                foreach ($budgetOptions as $b) {
                    $href = "BrowseGigs.php?budget=" . urlencode($b) . $q . $d . $s;
                    $items[] = ['text' => $b, 'href' => $href];
                }
                $selectedIndex = array_search($budget, $budgetOptions);
                $selectedIndex = $selectedIndex !== false ? $selectedIndex : 0;
                $boldFirst = true;
                include __DIR__ . '/../components/Dropdown.php';
                ?>

                <!-- DELIVERY TIME DROPDOWN - FIXED & CONSISTENT -->
                <?php
                $label = "Delivery Time";
                $isFilter = true;

                $q = $query ? '&query=' . urlencode($query) : '';
                $b = $budget !== 'Any' ? '&budget=' . urlencode($budget) : '';
                $s = $sort !== 'newest' ? '&sort=' . $sort : '';

                $deliveryOptions = [
                    'Any'        => 'Any',
                    '24 hours'   => '24 hours',
                    '3 days'     => '3 days',
                    '7 days'     => '7 days',
                    '10 days'    => '10 days',
                    '14 days'    => '14 days'
                ];

                $items = [];
                foreach ($deliveryOptions as $value => $text) {
                    $href = "BrowseGigs.php?delivery=" . urlencode($value) . $q . $b . $s;
                    $items[] = ['text' => $text, 'href' => $href];
                }

                $currentDeliveryDisplay = $deliveryOptions[$delivery] ?? 'Any';
                $selectedIndex = array_search($currentDeliveryDisplay, array_column($items, 'text'));
                $selectedIndex = $selectedIndex !== false ? $selectedIndex : 0;

                include __DIR__ . '/../components/Dropdown.php';
                ?>

            </div>

            <div class="sort-section">
                <?php
                $label = "Sort by";
                $isFilter = true;
                $rightAlign = true;

                $q = $query ? "&query=" . urlencode($query) : '';
                $b = $budget !== 'Any' ? "&budget=" . urlencode($budget) : '';
                $d = $delivery !== 'Any' ? "&delivery=" . urlencode($delivery) : '';

                $items = [
                    ['text' => 'Best selling',       'href' => "?sort=best_selling$q$b$d"],
                    ['text' => 'Newest',             'href' => "?sort=newest$q$b$d"],
                    ['text' => 'Price: Low to High', 'href' => "?sort=price_low$q$b$d"],
                    ['text' => 'Price: High to Low', 'href' => "?sort=price_high$q$b$d"],
                ];

                $selectedIndex = match ($sort) {
                    'price_low'     => 2,
                    'price_high'    => 3,
                    'best_selling'  => 0,
                    default         => 1
                };

                include __DIR__ . '/../components/Dropdown.php';
                ?>
            </div>
        </div>

        <?php if ($query !== ''): ?>
            <div class="results-title">
                <p>Results for: <strong><?= htmlspecialchars($query) ?></strong></p>
            </div>
            <div class="results-count">
                <p><?= count($filtered) ?> result<?= count($filtered) !== 1 ? 's' : '' ?></p>
            </div>
            <a class="clear-search" href="BrowseGigs.php">Clear search</a>
        <?php endif; ?>

        <div class="gigs-grid">
            <?php if (empty($filtered)): ?>
                <p style="text-align:center; padding:60px; color:#666; font-size:18px;">
                    No gigs found matching your criteria.
                </p>
            <?php else: ?>
                <?php foreach ($filtered as $gig): ?>
                    <?php
                    $seller       = $gig['username'];
                    $isPro        = $gig['is_pro'] == 1;
                    $description  = $gig['description'] ?? 'No description provided.';
                    $ratingValue  = $gig['avg_rating'] > 0 ? number_format($gig['avg_rating'], 1) : '0.0';
                    $ratingCount  = (int)$gig['review_count'];
                    $price        = number_format($gig['price'], 2);
                    $image        = '../images/gig-image-placeholder.jpg';
                    $gigId        = $gig['id'];
                    $gigLink      = "GigDetails.php?id=" . $gig['id'];
                    ?>
                    <?php include __DIR__ . '/../components/GigCard.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Login Required Modal Script (unchanged - beautiful & working) -->
    <script>
    document.querySelectorAll('.gig-card a, .gig-card').forEach(card => {
        card.addEventListener('click', function(e) {
            const link = this.closest('a') || this.querySelector('a');
            if (!link) return;
            const href = link.getAttribute('href');
            if (!href || href === '#') return;

            <?php if (!isset($_SESSION['user_id'])): ?>
                e.preventDefault();
                showLoginModal(href);
            <?php endif; ?>
        });
    });

    function showLoginModal(gigUrl) {
        document.querySelector('#loginRequiredModal')?.remove();

        const modal = document.createElement('div');
        modal.id = 'loginRequiredModal';
        modal.innerHTML = `
            <div class="login-modal-overlay">
                <div class="login-modal">
                    <h2>Sign in required</h2>
                    <p>You need to be logged in to view this gig.</p>
                    <div class="login-modal-buttons">
                        <a href="../pages/Login.php" class="btn-primary">Log In</a>
                        <a href="../pages/SignUp.php" class="btn-secondary">Create Account</a>
                        <button type="button" class="btn-cancel" onclick="document.getElementById('loginRequiredModal')?.remove()">Cancel</button>
                    </div>
                </div>
            </div>
        `;

        const style = document.createElement('style');
        style.textContent = `
            #loginRequiredModal { position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; animation: fadeIn 0.3s ease; }
            .login-modal-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.75); backdrop-filter: blur(10px); display: flex; justify-content: center; align-items: center; }
            .login-modal { background: white; padding: 44px 40px; border-radius: 20px; text-align: center; max-width: 440px; width: 90%; box-shadow: 0 25px 60px rgba(0,0,0,0.3); animation: modalPop 0.4s ease; }
            .login-modal h2 { font-size: 30px; font-weight: 700; margin-bottom: 16px; color: #222; }
            .login-modal p { color: #555; font-size: 17px; margin-bottom: 36px; line-height: 1.5; }
            .login-modal-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
            .btn-primary, .btn-secondary, .btn-cancel { padding: 16px 32px; border-radius: 14px; font-weight: 600; font-size: 16px; text-decoration: none; transition: all 0.25s ease; min-width: 160px; cursor: pointer; }
            .btn-primary { background: #6c5ce7; color: white; border: none; }
            .btn-primary:hover { background: #5f3dc4; transform: translateY(-2px); }
            .btn-secondary { background: transparent; color: #6c5ce7; border: 2.5px solid #6c5ce7; }
            .btn-secondary:hover { background: #6c5ce7; color: white; }
            .btn-cancel { background: #f5f5f5; color: #666; border: none; }
            .btn-cancel:hover { background: #e0e0e0; }
            @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
            @keyframes modalPop { from { transform: scale(0.7) translateY(-40px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
            @media (max-width: 480px) { .login-modal { padding: 36px 24px; } .login-modal-buttons { flex-direction: column; } .btn-primary, .btn-secondary, .btn-cancel { width: 100%; } }
        `;

        document.head.appendChild(style);
        document.body.appendChild(modal);

        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.classList.contains('login-modal-overlay')) {
                modal.remove();
            }
        });
    }
    </script>
    <script src="../js/dropdown.js"></script>
</body>
</html>