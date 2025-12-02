<!-- HEADER -->
<header id="header">
    <!-- Logo Banner -->
    <a href="../index.php">
        <div class="logo-banner-div">
            <img id="logo-banner" src="../images/gigsta-logo.svg">
        </div>
    </a>

    <!-- [COMPONENT] Search Bar -->
    <?php 
        $width = "460px"; 
        include __DIR__ . '/SearchBar.php'; 
    ?>

    <!-- Header Links -->
    <div class="header-links">
        <!-- [COMPONENT] Navigation Link Dropdown: Explore -->
        <?php 
            $label = "Explore";
            $items = [
                ['text' => 'All Gigs', 'href' => '#'],
                ['text' => 'Graphics & Design', 'href' => '#'],
                ['text' => 'Digital Marketing', 'href' => '#'],
                ['text' => 'Writing & Translation', 'href' => '#'],
                ['text' => 'Video & Animation', 'href' => '#'],
                ['text' => 'Music & Audio', 'href' => '#'],
                ['text' => 'Programming & Tech', 'href' => '#']
            ];
            $navMode = true;
            include __DIR__ . '/Dropdown.php';
        ?>

        <a class="header-navigation-link" href="#">Become a Freelancer</a>
    </div>

    <!-- Auth Buttons -->
    <div id="auth-buttons">
        <a class="header-navigation-link" href="../pages/Login.php">Log In</a>
        <!-- [COMPONENT] Primary Button: Join -->
        <?php
            $label = "Join";
            $href = "../pages/SignUp.php";
            $navMode = true;
            include __DIR__ . '/PrimaryButton.php'; 
        ?>
    </div>
</header>