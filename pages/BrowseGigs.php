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
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 20;
$offset   = ($page - 1) * $perPage;

// ------------------------------------------------------------------
// Build the base SQL query (for both count and results)
// ------------------------------------------------------------------
$baseSql = "FROM gigs
            INNER JOIN users ON gigs.user_id = users.id
            WHERE users.role = 'gigster' AND gigs.status = 'active'";

$params = [];

// Search filter
if ($query !== '') {
    $baseSql .= " AND (gigs.title LIKE :q OR gigs.description LIKE :q OR users.username LIKE :q)";
    $params[':q'] = '%' . $query . '%';
}

// Budget filter
if ($budget !== 'Any') {
    switch ($budget) {
        case 'Under $50':
            $baseSql .= " AND gigs.price < 50";
            break;
        case '$50 - $100':
            $baseSql .= " AND gigs.price BETWEEN 50 AND 100";
            break;
        case '$100+':
            $baseSql .= " AND gigs.price > 100";
            break;
    }
}

// Delivery Time filter
if ($delivery !== 'Any') {
    if (in_array($delivery, ['24 hours', '3 days', '7 days', '10 days', '14 days'])) {
        $baseSql .= " AND gigs.delivery_time = :delivery";
        $params[':delivery'] = $delivery;
    } else {
        $delivery = 'Any';
    }
}

// ------------------------------------------------------------------
// 1. Get total count for pagination
// ------------------------------------------------------------------
$countSql = "SELECT COUNT(*) as total " . $baseSql;
$countStmt = $db->prepare($countSql);
foreach ($params as $key => $value) {
    $countStmt->bindValue($key, $value, SQLITE3_TEXT);
}
$countResult = $countStmt->execute()->fetchArray(SQLITE3_ASSOC);
$totalGigs = $countResult['total'];
$totalPages = max(1, ceil($totalGigs / $perPage));

// ------------------------------------------------------------------
// 2. Build final query with sorting and pagination
// ------------------------------------------------------------------
$sql = "SELECT gigs.*, users.username, users.role, users.is_pro
        $baseSql";

// Sorting
switch ($sort) {
    case 'price_low':
        $sql .= " ORDER BY gigs.price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY gigs.price DESC";
        break;
    case 'best_selling':
        $sql .= " ORDER BY gigs.created_at DESC"; // Placeholder
        break;
    case 'newest':
    default:
        $sql .= " ORDER BY gigs.created_at DESC";
        break;
}

$sql .= " LIMIT $perPage OFFSET $offset";

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
// Helper: Build URL with current filters
// ------------------------------------------------------------------
function buildUrl($overrides = []) {
    global $query, $budget, $sort, $delivery;
    $params = [
        'query'    => $query,
        'budget'   => $budget !== 'Any' ? $budget : null,
        'delivery' => $delivery !== 'Any' ? $delivery : null,
        'sort'     => $sort !== 'newest' ? $sort : null,
    ];
    $params = array_merge($params, $overrides);
    $params = array_filter($params); // remove nulls
    return 'BrowseGigs.php?' . http_build_query($params);
}

// Dropdown helpers
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
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta • Browse Gigs</title>

    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 40px 0 60px;
            flex-wrap: wrap;
        }
        .pagination a, .pagination span {
            padding: 10px 16px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            min-width: 44px;
            text-align: center;
            transition: all 0.2s ease;
        }
        .pagination a {
            background: #f8f9fa;
            color: #333;
        }
        .pagination a:hover {
            background: oklch(88.28% 0.181 94.46);
            color: white;
            transform: translateY(-2px);
        }
        .pagination .current {
            background: oklch(88.28% 0.181 94.46);
            color: white;
            font-weight: 700;
        }
        .pagination .disabled {
            color: #aaa;
            cursor: not-allowed;
        }
        @media (max-width: 600px) {
            h2 { font-size: 22px; }
            .search-results-container { padding: 0 16px; }
            .results-title p, .results-count p { font-size: 14px; }
            .pagination { gap: 6px; }
            .pagination a, .pagination span { padding: 8px 12px; font-size: 14px; }
        }
    </style>
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
                $items = [];
                foreach ($budgetOptions as $b) {
                    $href = buildUrl(['budget' => $b === 'Any' ? null : $b, 'page' => null]);
                    $items[] = ['text' => $b, 'href' => $href];
                }
                $selectedIndex = array_search($budget, $budgetOptions);
                $selectedIndex = $selectedIndex !== false ? $selectedIndex : 0;
                $boldFirst = true;
                include __DIR__ . '/../components/Dropdown.php';
                ?>

                <!-- DELIVERY TIME DROPDOWN -->
                <?php
                $label = "Delivery Time";
                $isFilter = true;
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
                    $href = buildUrl(['delivery' => $value === 'Any' ? null : $value, 'page' => null]);
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
                $items = [
                    ['text' => 'Best selling',       'href' => buildUrl(['sort' => 'best_selling', 'page' => null])],
                    ['text' => 'Newest',             'href' => buildUrl(['sort' => 'newest', 'page' => null])],
                    ['text' => 'Price: Low to High', 'href' => buildUrl(['sort' => 'price_low', 'page' => null])],
                    ['text' => 'Price: High to Low', 'href' => buildUrl(['sort' => 'price_high', 'page' => null])],
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
                <p><?= $totalGigs ?> result<?= $totalGigs !== 1 ? 's' : '' ?></p>
            </div>
            <a class="clear-search" href="BrowseGigs.php">Clear search</a>
        <?php else: ?>
            <div class="results-count" style="margin-top: 8px;">
                <p><?= $totalGigs ?> active gig<?= $totalGigs !== 1 ? 's' : '' ?></p>
            </div>
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

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= buildUrl(['page' => $page - 1]) ?>">« Previous</a>
                <?php else: ?>
                    <span class="disabled">« Previous</span>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);

                if ($start > 1) {
                    echo '<a href="' . buildUrl(['page' => 1]) . '">1</a>';
                    if ($start > 2) echo '<span>...</span>';
                }

                for ($i = $start; $i <= $end; $i++) {
                    if ($i == $page) {
                        echo '<span class="current">' . $i . '</span>';
                    } else {
                        echo '<a href="' . buildUrl(['page' => $i]) . '">' . $i . '</a>';
                    }
                }

                if ($end < $totalPages) {
                    if ($end < $totalPages - 1) echo '<span>...</span>';
                    echo '<a href="' . buildUrl(['page' => $totalPages]) . '">' . $totalPages . '</a>';
                }
                ?>

                <?php if ($page < $totalPages): ?>
                    <a href="<?= buildUrl(['page' => $page + 1]) ?>">Next »</a>
                <?php else: ?>
                    <span class="disabled">Next »</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Login Modal Script (unchanged) -->
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
        modal.innerHTML = `...`; // (your existing modal HTML – unchanged)

        const style = document.createElement('style');
        style.textContent = `...`; // (your existing styles – unchanged)

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