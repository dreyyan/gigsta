<?php
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../database/gig_reviews.php';

// GET parameters
$query  = trim($_GET['query'] ?? '');
$budget = $_GET['budget'] ?? 'Any';
$sort   = $_GET['sort'] ?? 'newest';
$delivery = $_GET['delivery'] ?? 'Any';

// ------------------------------------------------------------------
// Build the query
// ------------------------------------------------------------------
$sql = "SELECT gigs.*, users.username, users.role, users.is_pro
        FROM gigs
        INNER JOIN users ON gigs.user_id = users.id
        WHERE users.role = 'gigster'";

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

// Delivery Time filter
if ($delivery !== 'Any') {
    switch ($delivery) {
        case '24 hours':
            $sql .= " AND delivery_time = '24 hours'";
            break;
        case '3 days':
            $sql .= " AND delivery_time = '3 days'";
            break;
        case '7 days':
            $sql .= " AND delivery_time = '7 days'";
            break;
        case '14 days':
        case '30 days':
            $sql .= " AND delivery_time = :delivery";
            $params[':delivery'] = $delivery;
            break;
    }
}

// Sorting – THIS IS WHAT WAS BROKEN
switch ($sort) {
    case 'price_low':
        $sql .= " ORDER BY gigs.price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY gigs.price DESC";
        break;
    case 'best_selling':
        // change this later when you have sales data
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
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, SQLITE3_TEXT);
}
$result = $stmt->execute();

$filtered = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $ratingData = getGigRating($row['id'], $db);
    $row['avg_rating']    = $ratingData['avg_rating'];
    $row['review_count']  = $ratingData['review_count'];
    $filtered[] = $row;
}

// ------------------------------------------------------------------
// Dropdown data
// ------------------------------------------------------------------
$budgetOptions = ['Any', 'Under $50', '$50 - $100', '$100+'];
$selectedBudgetIndex = array_search($budget, $budgetOptions);
$selectedBudgetIndex = $selectedBudgetIndex !== false ? $selectedBudgetIndex : 0;

// For Sort By – current selected text
$sortTexts = [
    'best_selling' => 'Best selling',
    'newest'       => 'Newest',
    'price_low'    => 'Price: Low to High',
    'price_high'   => 'Price: High to Low',
];
$currentSortText = $sortTexts[$sort] ?? 'Newest';
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
                $q = $query ? urlencode($query) : '';
                $items = [];
                foreach ($budgetOptions as $index => $b) {
                    $items[] = [
                        'text' => $b,
                        'href' => "BrowseGigs.php?budget=" . urlencode($b) . ($q ? "&query=$q" : '')
                    ];
                }
                $selectedIndex = $selectedBudgetIndex;
                $boldFirst = true;
                include __DIR__ . '/../components/Dropdown.php';
                ?>

                <!-- DELIVERY TIME DROPDOWN -->
                <?php
                $label = "Delivery Time";
                $isFilter = true;

                $base = "BrowseGigs.php";
                $q = $query ? '&query=' . urlencode($query) : '';
                $b = $budget !== 'Any' ? '&budget=' . urlencode($budget) : '';
                $s = $sort !== 'newest' ? '&sort=' . $sort : '';

                $items = [
                    ['text' => 'Any',        'href' => $base . '?delivery=Any' . $q . $b . $s],
                    ['text' => '24 hours',   'href' => $base . '?delivery=24+hours' . $q . $b . $s],
                    ['text' => '3 days',     'href' => $base . '?delivery=3+days' . $q . $b . $s],
                    ['text' => '7 days',     'href' => $base . '?delivery=7+days' . $q . $b . $s],
                    ['text' => '14 days',    'href' => $base . '?delivery=14+days' . $q . $b . $s],
                    ['text' => '30 days',    'href' => $base . '?delivery=30+days' . $q . $b . $s],
                ];

                $deliveryTexts = ['Any' => 'Any', '24 hours' => '24 hours', '3 days' => '3 days', '7 days' => '7 days', '14 days' => '14 days', '30 days' => '30 days'];
                $currentDeliveryText = $deliveryTexts[$delivery] ?? 'Any';

                $selectedIndex = array_search($currentDeliveryText, array_column($items, 'text'));
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

                $items = [
                    ['text' => 'Best selling',       'href' => "?sort=best_selling$q$b"],
                    ['text' => 'Newest',             'href' => "?sort=newest$q$b"],
                    ['text' => 'Price: Low to High', 'href' => "?sort=price_low$q$b"],
                    ['text' => 'Price: High to Low', 'href' => "?sort=price_high$q$b"],
                ];

                // Find correct selected item
                $selectedIndex = array_search($currentSortText, array_column($items, 'text'));
                $selectedIndex = $selectedIndex !== false ? $selectedIndex : 1; // default "Newest"

                include __DIR__ . '/../components/Dropdown.php';
                ?>
            </div>
        </div>

        <?php if ($query !== ''): ?>
            <div class="results-title">
                <p>Results for: <strong><?= htmlspecialchars($query) ?></strong></p>
            </div>
            <div class="results-count">
                <p><?= count($filtered) ?> results</p>
            </div>
            <a class="clear-search" href="/pages/BrowseGigs.php">Clear search</a>
        <?php endif; ?>

        <div class="gigs-grid">
            <?php foreach ($filtered as $gig): ?>
                <?php
                $seller       = $gig['username'];
                $isPro        = $gig['is_pro'] == 1;
                $description  = $gig['description'] ?? 'No description provided.';
                $ratingValue  = $gig['avg_rating'] > 0 ? number_format($gig['avg_rating'], 1) : '0.0';
                $ratingCount  = $gig['review_count'];
                $price        = number_format($gig['price'], 2);
                $image        = '../images/gig-image-placeholder.jpg';
                $gigId        = $gig['id'];
                ?>
                <?php include __DIR__ . '/../components/GigCard.php'; ?>
            <?php endforeach; ?>
        </div>
    </main>
<script>
// Check login + show perfectly centered modal
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
    // Remove existing modal
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

    // Perfect centering + beautiful styles
    const style = document.createElement('style');
    style.textContent = `
        #loginRequiredModal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }
        .login-modal-overlay {
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.75);
            backdrop-filter: blur(10px);
        }
        .login-modal {
            background: white;
            padding: 44px 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 440px;
            width: 90%;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            animation: modalPop 0.4s ease;
            position: relative;
        }
        .login-modal h2 {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #222;
        }
        .login-modal p {
            color: #555;
            font-size: 17px;
            margin-bottom: 36px;
            line-height: 1.5;
        }
        .login-modal-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-primary, .btn-secondary, .btn-cancel {
            padding: 16px 32px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.25s ease;
            min-width: 160px;
            cursor: pointer;
        }
        .btn-primary {
            background: oklch(20.70% 0.038 265.07);
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background: oklch(88.28% 0.181 94.46);
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: transparent;
            color: oklch(88.28% 0.181 94.46);
            border: 2.5px solid oklch(88.28% 0.181 94.46);
        }
        .btn-secondary:hover {
            background: oklch(88.28% 0.181 94.46);
            color: white;
        }
        .btn-cancel {
            background: #f5f5f5;
            color: #666;
            border: none;
        }
        .btn-cancel:hover {
            background: #e0e0e0;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes modalPop {
            from { transform: scale(0.7) translateY(-40px); opacity: 0; }
            to { transform: scale(1) translateY(0); opacity: 1; }
        }
        @media (max-width: 480px) {
            .login-modal {
                padding: 36px 24px;
                width: 95%;
            }
            .login-modal-buttons {
                flex-direction: column;
            }
            .btn-primary, .btn-secondary, .btn-cancel {
                width: 100%;
            }
        }
    `;

    document.head.appendChild(style);
    document.body.appendChild(modal);

    // Close on overlay click
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