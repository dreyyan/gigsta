<?php
session_start();
$isLoggedIn = $_SESSION['logged_in'] ?? true; // true if logged in
$userRole   = $_SESSION['role'] ?? '';         // 'client', 'freelancer', etc.
?>

<header id="header">
    <div id="header-left-container">
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
            <?php if (!$isLoggedIn): ?>
                <?php
                // Use dynamic dropdown component for not logged-in users
                $label = "Explore";
                $items = [
                    ['text' => 'All Gigs', 'href' => 'pages/FindFreelancers.php?query=All'],
                    ['text' => 'Graphics & Design', 'href' => 'pages/FindFreelancers.php?query=Graphics+%26+Design'],
                    ['text' => 'Digital Marketing', 'href' => 'pages/FindFreelancers.php?query=Digital+Marketing'],
                    ['text' => 'Writing & Translation', 'href' => 'pages/FindFreelancers.php?query=Writing+%26+Translation'],
                    ['text' => 'Video & Animation', 'href' => 'pages/FindFreelancers.php?query=Video+%26+Animation'],
                    ['text' => 'Music & Audio', 'href' => 'pages/FindFreelancers.php?query=Music+%26+Audio'],
                    ['text' => 'Programming & Tech', 'href' => 'pages/FindFreelancers.php?query=Programming+%26+Tech']
                ];
                $boldFirst = true;
                include __DIR__ . '/NavigationLinkDropdown.php';
                ?>
            <?php else: ?>
                <?php
                // Logged-in client links as standard <a> elements
                $links = [
                    ['text' => 'Browse Gigs', 'href' => 'pages/FindFreelancers.php'],
                    ['text' => 'My Orders', 'href' => 'pages/MyOrders.php'],
                    ['text' => 'Messages', 'href' => 'pages/Chats.php'],
                ];
                foreach ($links as $link) {
                    echo '<a class="header-navigation-link" href="' . htmlspecialchars($link['href']) . '">' . htmlspecialchars($link['text']) . '</a>';
                }
                ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Auth Buttons -->
    <div id="auth-buttons">
        <?php if (!$isLoggedIn): ?>
            <a class="header-navigation-link" href="../pages/Login.php">Log In</a>
            <?php
                $label = "Join";
                $href = "../pages/SignUp.php";
                $id = "join-button";
                $navMode = true;
                $icon = "";
                include __DIR__ . '/PrimaryButton.php';
            ?>
        <?php else: ?>
            <!-- Logged-in client profile dropdown -->
            <div class="header-profile-container" id="profileDropdown">
                <img src="../images/profile-placeholder-icon.svg" alt="Profile Placeholder" class="profile-placeholder-icon">
                <div class="dropdown-content">
                    <a href="../pages/GigsterProfile.php">Profile</a>
                    <a href="../pages/Chats.php">Messages</a>
                    <a href="../pages/PrivacyAndSupport.php">Privacy & Support</a>
                    <a href="../pages/Login.php">Log Out</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileContainer = document.getElementById('profileDropdown');

    if (profileContainer) {
        profileContainer.addEventListener('click', function(e) {
            e.stopPropagation(); // prevent click from bubbling
            profileContainer.classList.toggle('active');
        });

        // Close dropdown if clicked outside
        document.addEventListener('click', function() {
            profileContainer.classList.remove('active');
        });
    }
});
</script>
