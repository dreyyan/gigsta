<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'gigster') {
    header("Location: /pages/Login.php");
    exit;
}

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../database/gig_reviews.php';

$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM gigs WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bindValue(1, $userId, SQLITE3_INTEGER);
$result = $stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Gigs • Gigsta</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="icon" href="/images/gigsta-logo-minimal.svg">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: 60px auto;
            padding: 20px;
        }
        h1 {
            font-size: 32px;
            color: #333;
            text-align: center;
        }
        .create-btn {
            display: block;
            width: 220px;
            margin: 30px auto;
            padding: 16px;
            background: oklch(88.28% 0.181 94.46);
            color: white;
            text-align: center;
            border-radius: 12px;
            font-weight: bold;
            text-decoration: none;
        }
        .create-btn:hover { background: oklch(92.28% 0.181 94.46); }

        .gigs {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }
        .gig {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .gig:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
        .gig img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .gig-content {
            padding: 20px;
        }
        .gig-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #222;
        }
        .gig-desc {
            color: #555;
            font-size: 15px;
            line-height: 1.5;
            height: 70px;
            overflow: hidden;
        }
        .gig-footer {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
        }
        .price { color: #27ae60; font-size: 20px; }
        .delivery { color: oklch(20.70% 0.038 265.07); }
        .no-gigs {
            text-align: center;
            font-size: 22px;
            color: #888;
            padding: 100px 20px;
        }
    </style>
</head>
<body>
    <?php include '../components/Header.php'; ?>

    <div class="container">
        <h1>My Gigs</h1>
        <a href="CreateGig.php" class="create-btn">+ Create New Gig</a>

        <div class="gigs">
            <?php 
            $found = false;
            while ($gig = $result->fetchArray(SQLITE3_ASSOC)) {
                $found = true;

                $rating = getGigRating($gig['id'], $db);
                $avg = $rating['avg_rating'] ? number_format($rating['avg_rating'], 1) : '0.0';
                $count = $rating['review_count'] ?? 0;
            ?>
                <div class="gig" onclick="location.href='GigDetails.php?id=<?= $gig['id'] ?>'" style="cursor:pointer;">
                    <img src="/images/gig-image-placeholder.jpg" alt="gig">
                    <div class="gig-content">
                        <div class="gig-title"><?= htmlspecialchars($gig['title']) ?></div>
                        <div class="gig-desc"><?= htmlspecialchars(substr($gig['description'] ?? 'No description.', 0, 120)) ?>...</div>
                        <div>
                        <div class="gig-footer">
                            <div class="price">$<?= number_format($gig['price'], 2) ?></div>
                            <div>
                                <small><?= $avg ?> ★ (<?= $count ?>)</small><br>
                                <span class="delivery"><?= $gig['delivery_time'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if (!$found): ?>
                <div class="no-gigs">
                    No gigs yet.<br><br>
                    <a href="CreateGig.php" style="color:#6c5ce7;font-weight:bold;">Create your first gig now!</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="../js/dropdown.js"></script>
</body>
</html>