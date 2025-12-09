<?php
session_start();
require_once __DIR__ . '/../components/InputGroup.php';
require_once __DIR__ . '/../components/SocialButton.php';

// Connect to database
try {
    $db = new \SQLite3(__DIR__ . '/../database/gigsta.db');
} catch (Exception $e) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit;
    }
    die("Database error");
}

// Detect AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Only process login on POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginUser = trim($_POST['loginUser'] ?? '');
    $loginPassword = $_POST['loginPassword'] ?? '';

    header('Content-Type: application/json');

    if (empty($loginUser) || empty($loginPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE email = :login OR username = :login LIMIT 1");
    $stmt->bindValue(':login', $loginUser, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user && password_verify($loginPassword, $user['password'])) {
        // Login successful
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'] ?? null;

        // === REMEMBER ME ===
        if (!empty($_POST['remember_me'])) {
            $selector = bin2hex(random_bytes(12));
            $token = random_bytes(32);
            $expires = time() + 86400 * 30; // 30 days

            // Remove old tokens
            $db->exec("DELETE FROM auth_tokens WHERE user_id = " . (int)$user['id']);

            // Save new token
            $hashedToken = password_hash($token, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO auth_tokens (user_id, selector, token, expires) VALUES (?, ?, ?, ?)");
            $stmt->bindValue(1, $user['id'], SQLITE3_INTEGER);
            $stmt->bindValue(2, $selector, SQLITE3_TEXT);
            $stmt->bindValue(3, $hashedToken, SQLITE3_TEXT);
            $stmt->bindValue(4, $expires, SQLITE3_INTEGER);
            $stmt->execute();

            // Set secure cookies
            setcookie('remember_selector', $selector, $expires, '/', '', true, true);
            setcookie('remember_token', bin2hex($token), $expires, '/', '', true, true);
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'role' => $user['role'] ?? null
        ]);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email/username or password']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- [IMPORT] CSS -->
    <link rel="stylesheet" href="/css/primary-button.css">
    <link rel="stylesheet" href="/css/authentication.css">
    <link rel="stylesheet" href="/css/auth-mobile.css">
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
            <a href="/index.php">
                <img src="/images/gigsta-logo-minimal.svg" alt="Gigsta logo">
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
                <form class="auth-form" action="Login.php" method="post" id="loginForm">
                    <!-- Step 1: Email / Username -->
                    <?php renderInputGroup([
                        'name' => 'loginUser',
                        'id' => 'loginUser',
                        'placeholder' => 'Email or username',
                        'icon' => '/images/email-icon.svg',
                        'required' => true
                    ]); ?>

                    <?php renderInputGroup([
                        'type' => 'password',
                        'name' => 'loginPassword',
                        'id' => 'loginPassword',
                        'placeholder' => 'Password',
                        'icon' => '/images/password-icon.svg',
                        'required' => true
                    ]); ?>

                    <div class="remember-me-container">
                        <label class="remember-me-label">
                            <input type="checkbox" name="remember_me" id="remember_me" value="1">
                            <span class="checkmark"></span>
                            Remember me
                        </label>
                    </div>

                    <!-- [SECTION] Options -->
                    <!-- <div class="options-container">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            Remember Me
                        </label>
                        <a class="forgot-link" href="ForgotPassword.php">Forgot Password?</a>
                    </div> -->

                    <!-- Login Button -->
                    <?php
                    $label = "Login";
                    $id = "login-btn";
                    require_once __DIR__ . '/../components/PrimaryButton.php';
                    ?>

                    <!-- [COMPONENT] Divider -->
                    <div class="divider-container">
                        <img src="/images/or-line-divider.svg" class="line-divider">
                        <span>or</span>
                        <img src="/images/or-line-divider.svg" class="line-divider">
                    </div>

                    <!-- [SECTION] Social Buttons -->
                    <?php renderSocialButton([
                        'label' => 'Continue with Google',
                        'icon'  => '/images/google-icon.png',
                        'class' => 'google',
                        'onClick' => "window.location.href='/auth/google'"
                    ]); ?>

                    <?php renderSocialButton([
                        'label' => 'Continue with Facebook',
                        'icon'  => '/images/fb-icon.png',
                        'class' => 'facebook',
                        'onClick' => "window.location.href='/auth/facebook'"
                    ]); ?>

                    <!-- Redirect Link -->
                    <p class="redirect-link">
                        Don't have an account?
                        <a href="SignUp.php">Sign Up</a>
                    </p>
                </form>
            </div>
        </section>

        <!-- [SECTION] Right Auth Container -->
        <section class="auth-right-container">
            <div class="auth-hero">
                <div id="header-cont">
                    <h2 class="hero-title">Get Gigs Done at &nbsp;<span class="highlight">Lightning Speed.</span></h2>
                    <p class="hero-description">Fixed-price gigs delivered in hours, not weeks.</p>
                </div>

                <!-- Hero Image -->
                <div class="hero-image">
                    <img src="/images/odette.jpg" alt="Hero image">
                </div>
            </div>
        </section>
    </main>

    <!-- [IMPORT] JavaScript -->
    <script src="/js/login.js"></script>
</body>
</html>