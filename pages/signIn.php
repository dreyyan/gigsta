<?php
// [IMPORT] Components
require_once __DIR__ . '/../components/InputGroup.php';
require_once __DIR__ . '/../components/SocialButton.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Sign In - Gigsta</title>
</head>

<body>

    <main class="auth-wrapper">
        <section class="auth-left">
            <div class="auth-logo">
                <img src="../images/gigsta-logo-minimal.svg" alt="Gigsta logo">
            </div>

            <div class="auth-card">
                <h1 class="auth-title">Login</h1>
                <p class="auth-subtitle">Great to see you again, Gigsta!</p>

                <form class="auth-form" action="#" method="post" id="loginForm">
                    <!-- [INPUT FIELD] Username / Email -->
                    <?php renderInputGroup([
                        'name' => 'loginUser',
                        'id' => 'loginUser',
                        'placeholder' => 'Email or username',
                        'icon' => '../images/email-icon.svg',
                        'required' => true
                    ]); ?>

                    <!-- [INPUT FIELD] Password -->
                    <?php renderInputGroup([
                        'type' => 'password',
                        'name' => 'loginPassword',
                        'id' => 'loginPassword',
                        'placeholder' => 'Password',
                        'icon' => '../images/password-icon.svg',
                        'required' => true
                    ]); ?>

                    <label class="input-group">
                        <img src="https://via.placeholder.com/20x20?text=@" alt="user icon" class="input-icon">
                        <input type="text" name="username" placeholder="Email or username" required>
                    </label>

                    <label class="input-group">
                        <img src="https://via.placeholder.com/20x20?text=🔒" alt="password icon" class="input-icon">
                        <input type="password" name="password" placeholder="Password" required>
                    </label>

                    <div class="auth-row">
                        <label class="checkbox-label"><input type="checkbox" name="remember"> Remember Me</label>
                        <a class="forgot-link" href="#">Forgot Password?</a>
                    </div>

                    <!-- [COMPONENT] Primary Button: Login -->
                    <?php $label = "Login"; $href = "#"; include '../components/PrimaryButton.php'; ?>

                    <button class="primary-action" type="submit">Login</button>

                    <div class="divider"><span>or</span></div>

                    <!-- OAuth Buttons -->
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

                    <button class="social-btn google" type="button">
                        <img src="https://via.placeholder.com/18x18?text=G" alt="Google"> Continue with Google
                    </button>

                    <button class="social-btn facebook" type="button">
                        <img src="https://via.placeholder.com/18x18?text=F" alt="Facebook"> Continue with Facebook
                    </button>

                    <p class="signup-link">Don't have an account? <a href="signUp.php">Sign Up</a></p>
                </form>
            </div>
        </section>

        <section class="auth-right">
            <div class="auth-hero">
                <h2 class="hero-title">Get Gigs Done at <span class="highlight">Lightning Speed.</span></h2>
                <p class="hero-sub">Fixed-price gigs delivered in hours, not weeks.</p>

                <div class="hero-image">
                    <img src="../images/odette.jpg" alt="Hero image">
                </div>

                <div class="carousel-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
