<?php
// Sample gigs data
$gigs = [
    [
        'image' => '',
        'seller' => 'Jane Doe',
        'isPro' => true,
        'description' => 'Professional web design services...',
        'ratingValue' => 4.9,
        'ratingCount' => 120,
        'price' => 500
    ],
    [
        'image' => '',
        'seller' => 'John Smith',
        'isPro' => false,
        'description' => 'Creative logo design for your brand...',
        'ratingValue' => 5.0,
        'ratingCount' => 87,
        'price' => 200
    ],
    [
        'image' => '',
        'seller' => 'Alice Brown',
        'isPro' => true,
        'description' => 'Boost your website SEO and rankings...',
        'ratingValue' => 4.8,
        'ratingCount' => 50,
        'price' => 300
    ],
];
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

    <!-- [COMPONENT] Header -->
    <?php include '../components/Header.php'; ?>

    <main class="search-results-container">

        <!-- FILTERS AND RESULTS COUNT -->
        <div class="filters-section">
            <div class="filter-buttons">
                <?php
                $label = "Budget";
                $items = [
                    ['text' => 'Any', 'href' => '#'],
                    ['text' => 'Under $50', 'href' => '#'],
                    ['text' => '$50 - $100', 'href' => '#'],
                    ['text' => '$100+', 'href' => '#'],
                ];
                $boldFirst = true;
                include __DIR__ . '/../components/NavigationLinkDropdown.php';

                $label = "Delivery Time";
                $items = [
                    ['text' => 'Any', 'href' => '#'],
                    ['text' => '24 hours', 'href' => '#'],
                    ['text' => '3 days', 'href' => '#'],
                    ['text' => '7 days', 'href' => '#'],
                ];
                $boldFirst = true;
                include __DIR__ . '/../components/NavigationLinkDropdown.php';
                ?>
            </div>

            <div class="sort-section">
                <?php
                $label = "Sort by";
                $items = [
                    ['text' => 'Best selling', 'href' => '#'],
                    ['text' => 'Newest', 'href' => '#'],
                    ['text' => 'Price: Low to High', 'href' => '#'],
                    ['text' => 'Price: High to Low', 'href' => '#'],
                ];
                $rightAlign = true;
                $activeIndex = 0;
                include __DIR__ . '/../components/NavigationLinkDropdown.php';
                ?>
            </div>
        </div>

        <!-- RESULTS COUNT -->
        <div class="results-count">
            <p>9 results</p>
        </div>

        <!-- GIG LISTINGS GRID -->
        <div class="gigs-grid">
            <?php foreach ($gigs as $gig): extract($gig); include __DIR__ . '/../components/GigCard.php'; ?>
            <?php endforeach; ?>
        </div>

    </main>
    <script src="../js/dropdown.js"></script>
</body>
</html>