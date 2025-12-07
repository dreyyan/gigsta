<?php
session_start();
$isLoggedIn = $_SESSION['logged_in'] ?? false;
$userRole   = $_SESSION['role'] ?? '';
?>

<header id="header">
    <!-- [LEFT] Logo + Optional Search -->
    <div id="header-left-container">
        <a href="/gigsta/index.php">
            <div class="logo-banner-div">
                <img id="logo-banner" src="/gigsta/images/gigsta-logo.svg" alt="Gigsta Logo">
            </div>
        </a>

        <!-- [LOGGED IN] Search Bar -->
        <?php if ($isLoggedIn): ?>
            <?php
                $width = "460px";
                include __DIR__ . '/SearchBar.php';
            ?>
        <?php endif; ?>
    </div>

    <!-- [RIGHT] Navigation / Auth / Profile -->
    <div id="auth-buttons">
        <?php if ($isLoggedIn): ?>
            <!-- Logged-in navigation links -->
            <div class="header-links">
                <?php
                $links = [
                    ['text' => 'Browse Gigs', 'href' => '/gigsta/pages/BrowseGigs.php'],
                    ['text' => 'My Orders', 'href' => '/gigsta/pages/MyOrders.php'],
                    ['text' => 'Messages', 'href' => '/gigsta/pages/Chats.php'],
                ];
                foreach ($links as $link) {
                    echo '<a class="header-navigation-link" href="' . htmlspecialchars($link['href']) . '">' 
                        . htmlspecialchars($link['text']) . '</a>';
                }
                ?>
            </div>

            <!-- Profile Dropdown -->
            <div class="header-profile-container" id="profileDropdown">
                <img src="/gigsta/images/profile-placeholder-icon.svg" class="profile-placeholder-icon" alt="Profile">
                <div class="dropdown-content">
                    <a href="/gigsta/pages/Profile.php">Profile</a>
                    <a href="/gigsta/pages/Chats.php">Messages</a>
                    <a href="/gigsta/pages/PrivacyAndSupport.php">Privacy & Support</a>
                    <a href="/gigsta/database/logout.php">Log Out</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Logged-out navigation -->
            <div id="navigation-link">
                <a class="header-navigation-link" href="/gigsta/pages/SignUp.php">Become a Freelancer</a>
            </div>

            <!-- Auth buttons -->
            <a class="header-navigation-link" href="/gigsta/pages/Login.php">Log In</a>
            <?php
                $label = "Join";
                $href = "/gigsta/pages/SignUp.php";
                $navMode = true;
                include __DIR__ . '/PrimaryButton.php';
            ?>
        <?php endif; ?>
    </div>
</header>