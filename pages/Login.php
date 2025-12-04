<?php
// [IMPORT] PHP Components
require_once __DIR__ . '/../components/InputGroup.php';
require_once __DIR__ . '/../components/SocialButton.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- [IMPORT] CSS -->
    <link rel="stylesheet" href="../css/authentication.css">
    <!-- [IMPORT] Website Icon -->
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <!-- [IMPORT] Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta • Log In</title>
</head>

<body>
    <!-- [SECTION] Left Auth Container -->
    <main class="auth-container">
        <!-- [SECTION] Left Auth Container -->
        <section class="auth-left-container">
            <!-- [COMPONENT] Minimal Logo -->
            <a href="../index.php">
                <img src="../images/gigsta-logo-minimal.svg" alt="Gigsta logo">
            </a>

            <!-- [SECTION] Auth Content -->
            <div class="auth-content-container">
                <h1 class="auth-title">Log In</h1>
                <p class="auth-subtitle">Great to see you again, Gigsta!</p>

                <!-- [SECTION] Authentication Form -->
                <form class="auth-form" action="#" method="post" id="loginForm">
                    <!-- Step 1: Email / Username -->
                    <?php renderInputGroup([
                        'name' => 'loginUser',
                        'id' => 'loginUser',
                        'placeholder' => 'Email or username',
                        'icon' => '../images/email-icon.svg',
                        'required' => true
                    ]); ?>

                    <?php renderInputGroup([
                        'type' => 'password',
                        'name' => 'loginPassword',
                        'id' => 'loginPassword',
                        'placeholder' => 'Password',
                        'icon' => '../images/password-icon.svg',
                        'required' => true
                    ]); ?>

                    <!-- [SECTION] Options -->
                    <div class="options-container">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            Remember Me
                        </label>
                        <a class="forgot-link" href="ForgotPassword.php">Forgot Password?</a>
                    </div>

                    <!-- Login Button -->
                    <?php
                    $label = "Login";
                    $id = "login-btn";
                    include '../components/PrimaryButton.php';
                    ?>

                    <!-- [COMPONENT] Divider -->
                    <div class="divider-container">
                        <img src="../images/or-line-divider.svg" class="line-divider">
                        <span>or</span>
                        <img src="../images/or-line-divider.svg" class="line-divider">
                    </div>

                    <!-- [SECTION] Social Buttons -->
                    <?php renderSocialButton([
                        'label' => 'Continue with Google',
                        'icon'  => '../images/google-icon 1.png',
                        'class' => 'google',
                        'onClick' => "window.location.href='/auth/google'"
                    ]); ?>

                    <?php renderSocialButton([
                        'label' => 'Continue with Facebook',
                        'icon'  => '../images/fb-icon 1.png',
                        'class' => 'facebook',
                        'onClick' => "window.location.href='/auth/facebook'"
                    ]); ?>

                    <!-- Sign Up Link -->
                    <p class="signup-link">Don't have an account?<a href="signUp.php">Sign Up</a></p>
                </form>
            </div>
        </section>

        <!-- [SECTION] Right Auth Container -->
        <section class="auth-right-container">
            <div class="auth-hero">
                <div>
                    <h2 class="hero-title">Get Gigs Done at &nbsp;<span class="highlight">Lightning Speed.</span></h2>
                    <p class="hero-description">Fixed-price gigs delivered in hours, not weeks.</p>
                </div>

                <!-- Hero Image -->
                <div class="hero-image">
                    <img src="../images/odette.jpg" alt="Hero image">
                </div>
            </div>
        </section>
    </main>

    <!-- [IMPORT] JavaScript -->
    <script src="../js/login.js"></script>
</body>
</html>