<?php
// Sample gigs data
$gigs = [
    [
        'image' => 'https://via.placeholder.com/250x200?text=Web+Design',
        'seller' => 'Jane Doe',
        'isPro' => true,
        'description' => 'Professional web design services...',
        'ratingValue' => 4.9,
        'ratingCount' => 120,
        'price' => 500
    ],
    [
        'image' => 'https://via.placeholder.com/250x200?text=Logo+Design',
        'seller' => 'John Smith',
        'isPro' => false,
        'description' => 'Creative logo design for your brand...',
        'ratingValue' => 5.0,
        'ratingCount' => 87,
        'price' => 200
    ],
    [
        'image' => 'https://via.placeholder.com/250x200?text=SEO+Services',
        'seller' => 'Alice Brown',
        'isPro' => true,
        'description' => 'Boost your website SEO and rankings...',
        'ratingValue' => 4.8,
        'ratingCount' => 50,
        'price' => 300
    ],
    // Add more gigs as needed
];
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- [IMPORT] CSS -->
        <link rel="stylesheet" href="../css/styles.css">

        <!-- [IMPORT] Website Icon -->
        <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">

        <!-- [IMPORT] Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">

        <title>Gigsta: Find Freelancers</title>
    </head>

    <body>
        <!-- [COMPONENT] Header -->
        <?php include '../components/Header.php'; ?>

        <!--- MAIN SEARCH RESULTS -->
        <main class="search-results-container">

            <!-- FILTERS AND RESULTS COUNT -->
            <div class="filters-section">
                <div class="filter-buttons">
                    <button class="filter-btn">Budget <img src="../images/dropdown-arrow-icon.svg" alt="dropdown" class="dropdown-icon"></button>
                    <button class="filter-btn">Delivery Time <img src="../images/dropdown-arrow-icon.svg" alt="dropdown" class="dropdown-icon"></button>
                </div>

                <div class="sort-section">
                    <span>Sort by: <strong>Best selling</strong> <img src="../images/dropdown-arrow-icon.svg" alt="dropdown" class="dropdown-icon"></span>
                </div>
            </div>

            <!-- RESULTS COUNT -->
            <div class="results-count">
                <p>9 results</p>
            </div>

            <!-- GIG LISTINGS GRID -->
            <div class="gigs-grid">
                <?php
                foreach ($gigs as $gig) {
                    extract($gig);
                    include '../components/GigCard.php';
                }
                ?>
            </div>

        </main>

    </body>
    </html>