<?php
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../database/gig_reviews.php';  // Provides getGigRating()

$query  = trim($_GET['query'] ?? '');
$budget = $_GET['budget'] ?? 'Any';

// Base query: include `is_pro` field from users
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
            $sql .= " AND gigs.price < :budget";
            $params[':budget'] = 50;
            break;
        case '$50 - $100':
            $sql .= " AND gigs.price BETWEEN :min AND :max";
            $params[':min'] = 50;
            $params[':max'] = 100;
            break;
        case '$100+':
            $sql .= " AND gigs.price > :budget";
            $params[':budget'] = 100;
            break;
    }
}

$sql .= " ORDER BY gigs.created_at DESC";

// Prepare statement
$stmt = $db->prepare($sql);
foreach ($params as $k => $v) {
    $type = is_int($v) || is_float($v) ? SQLITE3_FLOAT : SQLITE3_TEXT;
    $stmt->bindValue($k, $v, $type);
}

$result = $stmt->execute();
$filtered = [];

// Fetch results + append rating info
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $ratingData = getGigRating($row['id'], $db);

    $row['avg_rating']   = $ratingData['avg_rating'];
    $row['review_count'] = $ratingData['review_count'];

    $filtered[] = $row;
}

// Dropdown options
$budgetOptions       = ['Any', 'Under $50', '$50 - $100', '$100+'];
$selectedBudgetIndex = array_search($budget, $budgetOptions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta • Find Freelancers</title>
</head>
<body>
    <?php include '../components/Header.php'; ?>

    <main class="search-results-container">

        <!-- FILTERS -->
        <div class="filters-section">
            <div class="filter-buttons">
                <?php
                // Budget dropdown
                $label = "Budget";
                $q = isset($_GET['query']) ? urlencode($_GET['query']) : '';
                $items = [];
                foreach ($budgetOptions as $index => $b) {
                    $items[] = [
                        'text' => $b,
                        'href' => "/pages/FindFreelancers.php?budget=" . urlencode($b) . "&query=$q"
                    ];
                }
                $boldFirst     = true;
                $selectedIndex = $selectedBudgetIndex !== false ? $selectedBudgetIndex : 0;
                include __DIR__ . '/../components/Dropdown.php';

                // Delivery Time dropdown
                $label = "Delivery Time";
                $items = [
                    ['text' => 'Any',      'href' => '#'],
                    ['text' => '24 hours', 'href' => '#'],
                    ['text' => '3 days',   'href' => '#'],
                    ['text' => '7 days',   'href' => '#'],
                ];
                $boldFirst = true;
                include __DIR__ . '/../components/Dropdown.php';
                ?>
            </div>

            <div class="sort-section">
                <?php
                $label = "Sort by";
                $items = [
                    ['text'=>'Best selling',       'href'=>'#'],
                    ['text'=>'Newest',             'href'=>'#'],
                    ['text'=>'Price: Low to High', 'href'=>'#'],
                    ['text'=>'Price: High to Low', 'href'=>'#'],
                ];
                $rightAlign  = true;
                $activeIndex = 0;
                include __DIR__ . '/../components/Dropdown.php';
                ?>
            </div>
        </div>

        <!-- RESULTS HEADER -->
        <?php if ($query !== ''): ?>
            <div class="results-title">
                <p>Results for: <strong><?= htmlspecialchars($query) ?></strong></p>
            </div>
            <div class="results-count">
                <p><?= count($filtered) ?> results</p>
            </div>
            <a class="clear-search" href="/pages/FindFreelancers.php">Clear search</a>
        <?php endif; ?>

        <!-- GIGS GRID -->
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
                $gigId        = $gig['id'];  // ← THIS IS KEY
                ?>
                <?php include __DIR__ . '/../components/GigCard.php'; ?>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="../js/dropdown.js"></script>
</body>
</html>
