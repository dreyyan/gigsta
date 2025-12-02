<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta: Get Gigs Done at Lightning Speed</title>
</head>

<body>
    <!-- [COMPONENT] Header -->
    <?php include 'components/Header.php'; ?>

    <main class="hero-container">
        <!---- HERO TITLE ---->
        <div class="hero-section-header">
            <h1>Get Gigs Done at</h1>
            <h1 id="hero-section-highlighted">Lightning Speed.</h1>
        </div>

        <!--- HERO DESCRIPTION --->
        <div class="hero-tagline">
            <h3>Fixed-price gigs delivered in hours, not weeks.</h3>
        </div>

        <!---- SEARCH BAR --->
        <div class="search-wrapper">
            <!-- [COMPONENT] Search Bar -->
            <?php $placeholder="Search for any service..."; $rounded=true; include 'components/SearchBar.php'; ?>

            <!-- [COMPONENT] Quick Tags -->
            <?php include 'components/QuickTags.php'; ?>

        </div>

        <!---- RATINGS ---->
        <div class="ratings">
            <div id="trust-content-container">
                <span>50k+ gigs completed •  4.9&nbsp;</span>
                <span id="star-icon">★&nbsp;</span>
                <span> • Secure payments • Proudly localized in Iloilo, Philippines</span>
            </div>
        </div>

        <!---- THREE INFO BOXES --->
        <div class="info-boxes">
            <!-- [COMPONENT] Feature Cards -->
            <?php
            $image="../images/thunder-icon.svg";
            $title="Delivered in Hours, Not Weeks";
            $description="Most gigs delivered same day or next day. Perfect for urgent projects and tight deadlines.";
            include 'components/FeatureCard.php'; ?>
            <?php
            $image="../images/globe-icon.svg";
            $title="Local Talent + Global Reach";
            $description="Hire top Ilonggo freelancers or reach skilled talent worldwide—same platform, same speed.";
            include 'components/FeatureCard.php'; ?>
            <?php
            $image="../images/dollar-icon.svg";
            $title="Fixed Price, Zero Drama";
            $description="No bidding wars. See the exact price before you order—every single time.";
            include 'components/FeatureCard.php'; ?>
        </div>

    </main>

</body>
</html>
