<?php
session_start();
require_once __DIR__ . '/../database/connection.php';

// === SECURITY: Must be logged in ===
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];
$statusFilter = $_GET['status'] ?? 'all';

// === CREATE orders TABLE IF IT DOESN'T EXIST (runs once) ===
$db->exec("
    CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        buyer_id INTEGER NOT NULL,
        gig_id INTEGER NOT NULL,
        status TEXT DEFAULT 'pending' CHECK(status IN ('pending','in_progress','completed','cancelled')),
        created_at TEXT DEFAULT (datetime('now')),
        FOREIGN KEY(buyer_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY(gig_id) REFERENCES gigs(id) ON DELETE CASCADE
    )
");

// === FETCH USER'S ORDERS ===
$sql = "
    SELECT 
        o.id AS order_id,
        o.status,
        o.created_at,
        g.id AS gig_id,
        g.title AS gig_title,
        g.price,
        g.delivery_time,
        u.username AS seller_username,
        u.is_pro
    FROM orders o
    INNER JOIN gigs g ON o.gig_id = g.id
    INNER JOIN users u ON g.user_id = u.id
    WHERE o.buyer_id = :user_id
";

if ($statusFilter !== 'all') {
    $sql .= " AND o.status = :status";
}

$sql .= " ORDER BY o.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
if ($statusFilter !== 'all') {
    $stmt->bindValue(':status', $statusFilter, SQLITE3_TEXT);
}
$result = $stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders • Gigsta</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/dropdown.css">
    <link rel="stylesheet" href="/css/primary-button.css">
    <link rel="icon" type="image/svg" href="/images/gigsta-logo-minimal.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
    body {
        background: var(--background);
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
    }

    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 24px;
    }

    h2 {
        font-family: 'DM Sans', sans-serif;
        font-size: 42px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 32px;
        color: var(--text);
    }

    .filters {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 32px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 10px 20px;
        border-radius: 12px;
        background: oklch(92% 0.02 265);
        color: var(--text);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.25s ease;
        border: 2px solid transparent;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--secondary);     /* Beautiful green accent */
        color: white;
        border-color: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(136, 40, 30, 0.15);
    }

    .orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 28px;
    }

    .order-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 35px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid oklch(95% 0.01 265);
    }

    .order-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }

    .order-image {
        height: 190px;
        background: #e8e8e8 url('/images/gig-image-placeholder.jpg') center/cover no-repeat;
        position: relative;
    }

    .order-image::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
    }

    .order-content {
        padding: 24px;
    }

    .order-title {
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0 0 10px;
        line-height: 1.4;
        color: var(--text);
    }

    .seller-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 14px 0;
        font-size: 0.95rem;
        color: oklch(40% 0.02 265);
    }

    .seller-info img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid var(--secondary);
    }

    .pro-badge {
        background: oklch(20% 0.04 265);
        color: white;
        font-size: 0.7rem;
        padding: 3px 9px;
        border-radius: 6px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .order-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 18px 0;
        font-size: 1rem;
        color: oklch(45% 0.02 265);
    }

    .price {
        font-size: 1.55rem;
        font-weight: 800;
        color: var(--secondary);
        font-family: 'DM Sans', sans-serif;
    }

    .status {
        padding: 7px 16px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status.pending     { background: oklch(92% 0.08 80);  color: oklch(50% 0.2 80); }
    .status.in_progress { background: oklch(88% 0.12 230); color: oklch(40% 0.25 230); }
    .status.completed   { background: var(--success);     color: white; }
    .status.cancelled   { background: var(--error);       color: white; }

    .actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .btn {
        padding: 12px 18px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        text-align: center;
        flex: 1;
        font-size: 0.95rem;
        transition: all 0.25s ease;
    }

    .btn-view {
        background: var(--secondary);
        color: white;
    }

    .btn-view:hover {
        background: oklch(80% 0.2 94);
        transform: translateY(-2px);
    }

    .btn-chat {
        background: transparent;
        color: var(--text);
        border: 2px solid oklch(88% 0.02 265);
    }

    .btn-chat:hover {
        background: oklch(95% 0.03 94);
        border-color: var(--secondary);
        color: var(--secondary);
    }

    .empty {
        text-align: center;
        padding: 100px 20px;
        color: oklch(60% 0.02 265);
    }

    .empty img {
        width: 140px;
        opacity: 0.4;
        margin-bottom: 24px;
        filter: grayscale(100%);
    }

    .empty h3 {
        font-size: 28px;
        margin-bottom: 12px;
        color: var(--text);
    }

    @media (max-width: 768px) {
        .orders-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .filters {
            gap: 10px;
        }
        .filter-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
    }
</style>
</head>
<body>
    <?php include '../components/Header.php'; ?>

    <div class="container">
        <h2>My Orders</h2>

        <div class="filters">
            <a href="MyOrders.php?status=all"       class="filter-btn <?= $statusFilter==='all' ? 'active' : '' ?>">All</a>
            <a href="MyOrders.php?status=pending"    class="filter-btn <?= $statusFilter==='pending' ? 'active' : '' ?>">Pending</a>
            <a href="MyOrders.php?status=in_progress"class="filter-btn <?= $statusFilter==='in_progress' ? 'active' : '' ?>">In Progress</a>
            <a href="MyOrders.php?status=completed"  class="filter-btn <?= $statusFilter==='completed' ? 'active' : '' ?>">Completed</a>
        </div>

        <?php if (!$result->fetchArray()): ?>
            <?php $result->reset(); // rewind pointer ?>
            <div class="empty">
                <img src="/images/empty-orders.svg" alt="No orders">
                <h3>No orders yet</h3>
                <p>Time to hire your first gigster!</p>
                <a href="BrowseGigs.php" style="margin-top:20px;display:inline-block;padding:12px 28px;background:#6c5ce7;color:white;border-radius:12px;text-decoration:none;font-weight:600;">
                    Browse Gigs
                </a>
            </div>
        <?php else: $result->reset(); ?>
            <div class="orders-grid">
                <?php while ($order = $result->fetchArray(SQLITE3_ASSOC)): ?>
                    <div class="order-card">
                        <div class="order-image"></div>
                        <div class="order-content">
                            <h3 class="order-title"><?= htmlspecialchars($order['gig_title']) ?></h3>
                            
                            <div class="seller-info">
                                <img src="/images/profile-placeholder-icon.svg" alt="">
                                <span><?= htmlspecialchars($order['seller_username']) ?></span>
                                <?php if ($order['is_pro']): ?><span class="pro-badge">PRO</span><?php endif; ?>
                            </div>

                            <div class="order-details">
                                <div>
                                    <strong>Delivery:</strong> <?= htmlspecialchars($order['delivery_time']) ?><br>
                                    <small>Ordered <?= date('M j, Y', strtotime($order['created_at'])) ?></small>
                                </div>
                                <div class="price">$<?= number_format($order['price'], 2) ?></div>
                            </div>

                            <div class="status <?= $order['status'] ?>">
                                <?= ucwords(str_replace('_', ' ', $order['status'])) ?>
                            </div>

                            <div class="actions">
                                <a href="GigDetails.php?id=<?= $order['gig_id'] ?>" class="btn btn-view">View Gig</a>
                                <a href="Chats.php?with=<?= $order['seller_username'] ?>" class="btn btn-chat">Message</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="/js/dropdown.js"></script>
</body>
</html>