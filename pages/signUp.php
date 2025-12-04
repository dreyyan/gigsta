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
    <!-- [IMPORT] CSS: Stylesheet -->
    <link rel="stylesheet" href="../css/authentication.css">
    <!-- [IMPORT] Website Icon -->
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <!-- [IMPORT] Fonts: Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Gigsta • Sign Up</title>
</head>

<body>
    <main class="auth-container">
        <!-- [SECTION] Left Auth Container -->
        <section class="auth-left-container">
            <!-- [COMPONENT] Minimal Logo -->
            <a href="../index.php">
                <img src="../images/gigsta-logo-minimal.svg" alt="Gigsta logo" id="gigsta-logo">
            </a>

            <!-- [SECTION] Auth Content -->
            <div class="auth-content-container">
                <h1 class="auth-title">Sign Up</h1>
                <p class="auth-subtitle">Welcome to Gigsta - let's get started!</p>

                <!-- [SECTION] Authentication Form -->
                <form class="auth-form" action="#" method="post" id="signUpForm">
                    <!-- Step 1: Email + Password + Confirm Password -->
                    <div class="form-step" id="step1">
                        <?php renderInputGroup([
                            'name' => 'signUpEmail',
                            'id' => 'signUpEmail',
                            'placeholder' => 'example@domain.com',
                            'icon' => '../images/email-icon.svg',
                            'required' => true
                        ]); ?>

                        <?php renderInputGroup([
                            'type' => 'password',
                            'name' => 'signUpPassword',
                            'id' => 'signUpPassword',
                            'placeholder' => '******',
                            'icon' => '../images/password-icon.svg',
                            'required' => true
                        ]); ?>

                        <?php renderInputGroup([
                            'type' => 'password',
                            'name' => 'signUpConfirm',
                            'id' => 'signUpConfirm',
                            'placeholder' => '******',
                            'icon' => '../images/password-icon.svg',
                            'required' => true
                        ]); ?>
                        
                        <ul class="password-requirements">
                            <li data-rule="length"><img src="../images/check-indicator-icon.svg" alt="check"> At least 8 characters</li>
                            <li data-rule="uppercase"><img src="../images/check-indicator-icon.svg" alt="check"> At least 1 uppercase letter</li>
                            <li data-rule="lowercase"><img src="../images/check-indicator-icon.svg" alt="check"> At least 1 lowercase letter</li>
                            <li data-rule="number"><img src="../images/check-indicator-icon.svg" alt="check"> At least 1 number</li>
                        </ul>

                        <!-- [COMPONENT: Primary Button] Continue -->
                        <?php
                        $label = "Continue";
                        $id = "primary-btn";
                        include '../components/PrimaryButton.php';
                        ?>
                    </div>

                    <!-- Step 2: Username -->
                    <div class="form-step" id="step2" style="display:none;">
                        <?php renderInputGroup([
                            'name' => 'signUpUsername',
                            'id' => 'signUpUsername',
                            'placeholder' => 'juandela_cruz01',
                            'icon' => '../images/user-icon.svg',
                            'required' => true
                        ]); ?>

                        <!-- [COMPONENT: Primary Button] Sign Up -->
                        <?php
                        $label = "Sign Up";
                        $id = "primary-btn2";
                        include '../components/PrimaryButton.php'; ?>
                    </div>

                    <!-- [COMPONENT] Divider -->
                    <div class="divider-container">
                        <img src="../images/or-line-divider.svg" class="line-divider">
                        <span>or</span>
                        <img src="../images/or-line-divider.svg" class="line-divider">
                    </div>

                    <!-- [SECTION] Social Buttons -->
                    <div id="social-buttons-container">
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
                    </div>
                    
                    <!-- Login Link -->
                    <p class="login-link">Already have an account?&nbsp;<a href="Login.php">Login</a></p>
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
    <script src="../js/signup.js"></script>
</body>
</html>