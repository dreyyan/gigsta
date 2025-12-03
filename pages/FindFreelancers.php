<?php
// Sample gigs data
$gigs = [
    ['title' => 'Web Development Service','image'=>'','seller'=>'Jane Doe','isPro'=>true,'description'=>'Professional web design services...','ratingValue'=>4.9,'ratingCount'=>120,'price'=>500],
    ['title' => 'Logo Design for Brands','image'=>'','seller'=>'John Smith','isPro'=>false,'description'=>'Creative logo design for your brand...','ratingValue'=>5.0,'ratingCount'=>87,'price'=>200],
    ['title' => 'SEO Optimization','image'=>'','seller'=>'Alice Brown','isPro'=>true,'description'=>'Boost your website SEO and rankings...','ratingValue'=>4.8,'ratingCount'=>50,'price'=>300],
];

// ---- SEARCH LOGIC ----
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$budget = isset($_GET['budget']) ? $_GET['budget'] : 'Any';

// Filter gigs by search query
$filtered = [];
foreach ($gigs as $gig) {
    if ($query === '' || stripos($gig['title'], $query) !== false) {
        $filtered[] = $gig;
    }
}

// Filter gigs by budget
if ($budget !== 'Any') {
    $filtered = array_filter($filtered, function($gig) use ($budget) {
        $price = $gig['price'];
        switch ($budget) {
            case 'Under $50': return $price < 50;
            case '$50 - $100': return $price >= 50 && $price <= 100;
            case '$100+': return $price > 100;
            default: return true;
        }
    });
}

// Budget options for dropdown
$budgetOptions = ["Any","Under $50","$50 - $100","$100+"];

// Determine selected index based on $_GET['budget']
$selectedBudgetIndex = array_search($budget, $budgetOptions);
if ($selectedBudgetIndex === false) $selectedBudgetIndex = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/styles.css">
<link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
<title>Gigsta: Find Freelancers</title>
</head>
<body>

    <!-- Header -->
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
$selectedIndex = $selectedBudgetIndex; // ✅ highlight current selection
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
