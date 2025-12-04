<?php
require_once __DIR__ . '/../database/db.php'; 

$query = trim($_GET['query'] ?? '');
$budget = $_GET['budget'] ?? 'Any';

// query merged users table (gigsters only)
$sql = "SELECT * FROM users WHERE role = 'gigster'";
$params = [];

if ($query !== '') {
    $sql .= " AND (full_name LIKE :q OR skills LIKE :q OR bio LIKE :q)";
    $params[':q'] = '%' . $query . '%';
}

// add budget filter
if ($budget !== 'Any') {
    switch ($budget) {
        case 'Under $50':
            $sql .= " AND hourly_rate < :budget";
            $params[':budget'] = 50;
            break;
        case '$50 - $100':
            $sql .= " AND hourly_rate BETWEEN :min AND :max";
            $params[':min'] = 50;
            $params[':max'] = 100;
            break;
        case '$100+':
            $sql .= " AND hourly_rate > :budget";
            $params[':budget'] = 100;
            break;
    }
}

$sql .= " ORDER BY created_at DESC";
$stmt = $db->prepare($sql);
foreach ($params as $k => $v) {
    $type = is_int($v) ? SQLITE3_INTEGER : SQLITE3_FLOAT;
    $stmt->bindValue($k, $v, $type);
}

$result = $stmt->execute();
$filtered = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $filtered[] = $row;
}

// Budget options for dropdown
$budgetOptions = ["Any","Under $50","$50 - $100","$100+"];
$selectedBudgetIndex = array_search($budget, $budgetOptions);
if ($selectedBudgetIndex === false) $selectedBudgetIndex = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- [IMPORT] CSS: Stylesheet -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header-navigation-link.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dropdown.css">
    <link rel="stylesheet" href="../css/hero-section.css">
    <link rel="stylesheet" href="../css/feature-card.css">
    <link rel="stylesheet" href="../css/quick-tags.css">
    <link rel="stylesheet" href="../css/primary-button.css">
    <link rel="stylesheet" href="../css/gig-card.css">
    <!-- [IMPORT] Website Icon -->
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <!-- [IMPORT] Fonts: Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta • Find Freelancers</title>
</head>
<body>
    <!-- [COMPONENT] Header -->
    <?php include '../components/Header.php'; ?>

    <main class="search-results-container">
        <!-- FILTERS -->
        <div class="filters-section">
            <div class="filter-buttons">
                <?php
                $label = "Budget";
                $q = isset($_GET['query']) ? urlencode($_GET['query']) : '';
                $items = [];
                foreach ($budgetOptions as $index => $b) {
                    $items[] = [
                        'text' => $b,
                        'href' => "/pages/FindFreelancers.php?budget=" . urlencode($b) . "&query=$q"
                    ];
                }
                $boldFirst = true;
                $selectedIndex = $selectedBudgetIndex;
                include __DIR__ . '/../components/Dropdown.php';

                // Delivery Time (currently placeholder)
                $label = "Delivery Time";
                $items = [
                    ['text' => 'Any', 'href' => '#'],
                    ['text' => '24 hours', 'href' => '#'],
                    ['text' => '3 days', 'href' => '#'],
                    ['text' => '7 days', 'href' => '#'],
                ];
                $boldFirst = true;
                include __DIR__ . '/../components/Dropdown.php';
                ?>
            </div>

            <div class="sort-section">
                <?php
                $label = "Sort by";
                $items = [
                    ['text'=>'Best selling','href'=>'#'],
                    ['text'=>'Newest','href'=>'#'],
                    ['text'=>'Price: Low to High','href'=>'#'],
                    ['text'=>'Price: High to Low','href'=>'#'],
                ];
                $rightAlign = true;
                $activeIndex = 0;
                include __DIR__ . '/../components/Dropdown.php';
                ?>
            </div>
        </div>

        <!-- RESULTS COUNT -->
        <?php if ($query !== ''): ?>
            <div class="results-title">
                <p>Results for: <strong><?= htmlspecialchars($query) ?></strong></p>
            </div>
            <div class="results-count">
                <p><?= count($filtered) ?> results</p>
            </div>
            <a class="clear-search" href="/pages/FindFreelancers.php">
                Clear search
            </a>
        <?php endif; ?>

        <!-- GRID -->
        <div class="gigs-grid">
            <?php foreach ($filtered as $gig): ?>
                <?php extract($gig); ?>
                <?php include __DIR__ . '/../components/GigCard.php'; ?>
            <?php endforeach; ?>

            <?php if (empty($filtered)): ?>
                <p>No gigs found<?= $query ? ' for "' . htmlspecialchars($query) . '"' : '' ?></p>
            <?php endif; ?>
        </div>

    </main>

<script src="../js/dropdown.js"></script>
</body>
</html>
