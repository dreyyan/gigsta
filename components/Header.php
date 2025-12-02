<!-- HEADER -->
<header id="header">
    <!-- Logo Banner -->
    <div class="logo-banner-div">
        <img id="logo-banner" src="../images/gigsta-logo.svg">
    </div>

    <!-- [COMPONENT] Search Bar -->
    <?php include 'components/SearchBar.php'; ?>

    <!-- Header Links -->
    <div class="header-links">
        <!-- [COMPONENT] Navigation Link Dropdown: Explore -->
        <?php $label="Explore"; include 'components/NavigationLinkDropdown.php'; ?>
        <a class="header-navigation-link" href="#">Become a Freelancer</a>
    </div>

    <!-- Auth Buttons -->
    <div id="auth-buttons">
        <a class="header-navigation-link" href="#">Sign In</a>
        <!-- [COMPONENT] Primary Button: Join -->
        <?php $label = "Join"; $href="#"; include 'components/PrimaryButton.php'; ?>
    </div>
</header>