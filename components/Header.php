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
            include __DIR__ . '/NavigationLinkDropdown.php'; 
        ?>

        <a class="header-navigation-link" href="#">Become a Freelancer</a>
    </div>

    <!-- Auth Buttons -->
    <div id="auth-buttons">
        <a class="header-navigation-link" href="../pages/SignIn.php">Sign In</a>
        <!-- [COMPONENT] Primary Button: Join -->
        <?php
            $label = "Join";
            $href = "../pages/SignUp.php";
            include __DIR__ . '/PrimaryButton.php'; 
        ?>
    </div>
</header>
