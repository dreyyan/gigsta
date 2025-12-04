<?php
require_once __DIR__ . '/../components/InputGroup.php';
require_once __DIR__ . '/../components/SocialButton.php';


try { // Connect to the database
    $db = new SQLite3(__DIR__ . '/../database/gigsta.db');
} catch (Exception $e) {
    die("Unable to connect to database: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') { //checks if form is submitted
    $loginUser = $_POST['loginUser'] ?? '';
    $loginPassword = $_POST['loginPassword'] ?? '';

    //fetch user from database
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
    $stmt->bindValue(':email', $loginUser, SQLITE3_TEXT);
    $stmt->bindValue(':username', $loginUser, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) { //verify password
        if (password_verify($loginPassword, $user['password'])) {
            session_start(); //log the user in
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            echo "Login successful! Welcome, " . $user['username'];
            header("Location: ../index.php"); //redirect to dashboard or homepage
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- [IMPORT] CSS -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/authentication.css">
    <link rel="stylesheet" href="../css/primary-button.css">
    <link rel="stylesheet" href="../css/social-button.css">
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

                <!-- Form Validation -->
                <?php if (!empty($error)) : ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
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
                        'icon'  => '../images/google-icon.png',
                        'class' => 'google',
                        'onClick' => "window.location.href='/auth/google'"
                    ]); ?>

                    <?php renderSocialButton([
                        'label' => 'Continue with Facebook',
                        'icon'  => '../images/fb-icon.png',
                        'class' => 'facebook',
                        'onClick' => "window.location.href='/auth/facebook'"
                    ]); ?>

                    <!-- Redirect Link -->
                    <p class="redirect-link">
                        Don't have an account?
                        <a href="signUp.php">Sign Up</a>
                    </p>
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