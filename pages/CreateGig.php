<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only gigsters can create gigs
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'gigster') {
    header("Location: /pages/Login.php");
    exit;
}

require_once __DIR__ . '/../database/connection.php';

$userId = $_SESSION['user_id'];
$success = $error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category    = $_POST['category'] ?? '';
    $price       = floatval($_POST['price'] ?? 0);
    $delivery    = $_POST['delivery_time'] ?? '';

    // Validation
    if (empty($title) || strlen($title) < 10) {
        $error = "Title must be at least 10 characters.";
    } elseif (empty($description) || strlen($description) < 50) {
        $error = "Description must be at least 50 characters.";
    } elseif ($price < 5) {
        $error = "Price must be at least $5.";
    } elseif (empty($category) || empty($delivery)) {
        $error = "Please fill in all fields.";
    } else {
        // Insert gig
        $stmt = $db->prepare("INSERT INTO gigs 
            (user_id, title, description, category, price, delivery_time, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'active')");

        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $title, SQLITE3_TEXT);
        $stmt->bindValue(3, $description, SQLITE3_TEXT);
        $stmt->bindValue(4, $category, SQLITE3_TEXT);
        $stmt->bindValue(5, $price, SQLITE3_FLOAT);
        $stmt->bindValue(6, $delivery, SQLITE3_TEXT);

        if ($stmt->execute()) {
            $success = "Gig created successfully! <a href='MyGigs.php' style='color:#6c5ce7;text-decoration:underline;'>View your gigs</a>";
        } else {
            $error = "Failed to create gig. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Gig Gigsta</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/primary-button.css">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f9fafe; margin: 0; }
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        h1 { font-size: 36px; color: #222; text-align: center; margin-bottom: 8px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 40px; }
        .form-group { margin-bottom: 24px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: border 0.2s;
            box-sizing: border-box;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: oklch(88.28% 0.181 94.46);
        }
        textarea { height: 160px; resize: vertical; }
        .price-input { display: flex; align-items: center; gap: 10px; }
        .price-input span { font-size: 18px; color: #666; }
        .submit-btn {
            background: oklch(88.28% 0.181 94.46);
            color: white;
            padding: 16px 32px;
            border: none;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .submit-btn:hover { background: oklch(90.28% 0.181 94.46); }
        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-weight: 500;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        @media (max-width: 600px) {
            .container { padding: 0 16px; }
            h1 { font-size: 28px; }
        }
    </style>
</head>
<body>
    <?php include '../components/Header.php'; ?>

    <div class="container">
        <h1>Create a New Gig</h1>
        <p class="subtitle">Share your skills and start earning on Gigsta!</p>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Gig Title</label>
                <input type="text" id="title" name="title" placeholder="e.g. I will design a modern logo for your brand" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <option value="Graphics & Design">Graphics & Design</option>
                    <option value="Programming & Tech">Programming & Tech</option>
                    <option value="Digital Marketing">Digital Marketing</option>
                    <option value="Video & Animation">Video & Animation</option>
                    <option value="Writing & Translation">Writing & Translation</option>
                    <option value="Music & Audio">Music & Audio</option>
                    <option value="Business">Business</option>
                    <option value="Lifestyle">Lifestyle</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Describe what you offer in detail..." required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price (USD)</label>
                <div class="price-input">
                    <span>$</span>
                    <input type="number" id="price" name="price" min="5" max="9999" step="1" placeholder="25" required>
                </div>
            </div>

            <div class="form-group">
                <label for="delivery_time">Delivery Time</label>
                <select id="delivery_time" name="delivery_time" required>
                    <option value="">Select delivery time</option>
                    <option value="24 hours">24 hours</option>
                    <option value="3 days">3 days</option>
                    <option value="5 days">5 days</option>
                    <option value="7 days">7 days</option>
                    <option value="10 days">10 days</option>
                    <option value="14 days">14 days</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Create Gig</button>
        </form>
    </div>
    <script src="../js/dropdown.js"></script>
</body>
</html>