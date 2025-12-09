<?php
// Disable warnings/notices for AJAX responses
error_reporting(E_ERROR | E_PARSE);

// [IMPORT] PHP Components
require_once __DIR__ . '/../components/InputGroup.php';
require_once __DIR__ . '/../components/SocialButton.php';

try {
    $db = new SQLite3(__DIR__ . '/../database/gigsta.db');
} catch (Exception $e) {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '' === 'XMLHttpRequest') {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['status'=>'error','message'=>'Database connection failed']);
        exit;
    } else {
        die('Database connection failed');
    }
}

// Handle AJAX POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['signUpEmail'] ?? '');
    $username = trim($_POST['signUpUsername'] ?? '');
    $password = trim($_POST['signUpPassword'] ?? '');
    $confirmPassword = trim($_POST['signUpConfirm'] ?? '');

    header('Content-Type: application/json');

    if (empty($email) || empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Please fill in all fields!']);
        exit;
    }

    if ($password !== $confirmPassword) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Passwords do not match!']);
        exit;
    }

    // Check if email exists
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($row['count'] > 0) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Email already exists!']);
        exit;
    }

    // Check if username exists
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($row['count'] > 0) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Username already taken!']);
        exit;
    }

    // Insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username,email,password) VALUES (:username,:email,:password)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);

    $result = @$stmt->execute(); // suppress warnings

    if ($result) {
        http_response_code(200);
        echo json_encode(['status'=>'success','message'=>'Registration successful']);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','message'=>'Database insert failed']);
    }

    exit; // stop PHP to avoid HTML output
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- [IMPORT] CSS: Stylesheet -->
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/primary-button.css">
    <link rel="stylesheet" href="/css/authentication.css">
    <link rel="stylesheet" href="/css/auth-mobile.css">
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
                <img src="/images/gigsta-logo-minimal.svg" alt="Gigsta logo" id="gigsta-logo">
            </a>

            <!-- [SECTION] Auth Content -->
            <div class="auth-content-container">
                <h1 class="auth-title">Sign Up</h1>
                <p class="auth-subtitle">Welcome to Gigsta - let's get started!</p>

                <!-- Form Validation -->
                <?php if (!empty($error)) : ?>
                    <div class="error-message" style="color:red; margin-bottom:10px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- [SECTION] Authentication Form -->
                <form class="auth-form" action="#" method="post" id="signUpForm">
                    <div>
                        <?php renderInputGroup([
                            'name' => 'signUpEmail',
                            'id' => 'signUpEmail',
                            'placeholder' => 'example@domain.com',
                            'icon' => '../images/email-icon.svg',
                            'required' => true
                        ]); ?>
                        
                        <?php renderInputGroup([
                            'name' => 'signUpUsername',
                            'id' => 'signUpUsername',
                            'placeholder' => 'juandelacruz_01',
                            'icon' => '../images/user-icon.svg',
                            'required' => true
                        ]); ?>

                        <?php renderInputGroup([
                            'type' => 'password',
                            'name' => 'signUpPassword',
                            'id' => 'signUpPassword',
                            'placeholder' => 'Password',
                            'icon' => '../images/password-icon.svg',
                            'required' => true
                        ]); ?>

                        <?php renderInputGroup([
                            'type' => 'password',
                            'name' => 'signUpConfirm',
                            'id' => 'signUpConfirm',
                            'placeholder' => 'Confirm password',
                            'icon' => '../images/password-icon.svg',
                            'required' => true
                        ]); ?>
                        
                        <ul class="password-requirements">
                            <li data-rule="length"><img src="/images/check-indicator-icon.svg" alt="check"> At least 8 characters</li>
                            <li data-rule="uppercase"><img src="/images/check-indicator-icon.svg" alt="check"> At least 1 uppercase letter</li>
                            <li data-rule="lowercase"><img src="/images/check-indicator-icon.svg" alt="check"> At least 1 lowercase letter</li>
                            <li data-rule="number"><img src="/images/check-indicator-icon.svg" alt="check"> At least 1 number</li>
                        </ul>

                        <!-- [COMPONENT: Primary Button] Sign Up -->
                        <?php
                        $label = "Sign Up";
                        $id = "primary-btn";
                        include '../components/PrimaryButton.php'; ?>
                    </div>
                    
                    <!-- Redirect Link -->
                    <p class="redirect-link">Already have an account?&nbsp;<a href="Login.php">Login</a></p>
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
                    <img src="/images/odette.png" alt="Hero image">
                </div>
            </div>
        </section>
    </main>

    <!-- [IMPORT] JavaScript -->
    <script src="/js/signup.js"></script>
</body>
</html>