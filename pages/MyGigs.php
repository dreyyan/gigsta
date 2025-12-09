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

/* UPDATE STATUS */
if (isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];

    $update = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update->bindValue(1, $status, SQLITE3_TEXT);
    $update->bindValue(2, $orderId, SQLITE3_INTEGER);
    $update->execute();

    header("Location: MyGigs.php?updated=1");
    exit;
}

/* FETCH GIGS */
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
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" href="/images/gigsta-logo-minimal.svg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>

/* PAGE CONTAINER */
.container {
    max-width: 1100px;
    margin: 60px auto;
    padding: 20px;
}

/* TITLE */
h1 {
    text-align: center;
    color: var(--text);
}

/* CREATE BTN */
.create-btn {
    display: block;
    width: 220px;
    margin: 30px auto;
    padding: 16px;
    background: var(--secondary);
    color: white;
    text-align: center;
    border-radius: 12px;
    font-weight: bold;
    text-decoration: none;
}
.create-btn:hover { background: oklch(92% 0.08 94.46); }

/* GRID */
.gigs {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
    margin-top: 40px;
}

/* CARD */
.gig {
    background: var(--background);
    border-radius: 16px;
    overflow: hidden;
    border: 2px solid rgba(0,0,0,0.05);
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    transition: 0.3s;
}
.gig:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

/* IMAGE */
.gig img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: 0.3s ease;
}
.gig:hover img { transform: scale(1.05); }

/* CONTENT */
.gig-content {
    padding: 22px;
}

.gig-title {
    font-size: 20px;
    font-family: var(--header);
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text);
}

.gig-desc {
    color: var(--text);
    opacity: 0.7;
    font-size: 15px;
    height: 70px;
    overflow: visible;
    margin-bottom: 24px;
}

/* FOOTER */
.gig-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;

    .price {
        font-family: var(--header);
        font-size: 28px;
    }
}

.price {
    color: var(--success);
    font-size: 22px;
    font-weight: bold;
}

.delivery { color: var(--primary); }

/* ORDERS BOX */
.orders-box {
    padding: 0px;
    border-radius: 12px;
    margin-top: 16px;

    h4 {
        margin-bottom: 12px;
    }
}

.order-item {
    background: var(--background);
    padding: 16px;
    border-radius: 10px;
    margin-bottom: 12px;
    border-left: 5px solid var(--primary);
    border: 1px solid var(--primary);
    
}

.order-item p {
    color: var(--text);
    margin-bottom: 6px;
}

.order-item b {
    font-family: var(--header);
}

/* ================================
   STATUS DROPDOWN (from MyOrders)
================================*/

/* EXACT MyOrders colors */
.status.pending     { background: oklch(92% 0.08 80);  color: oklch(50% 0.2 80); font-weight: bold; margin-right: 2px;}
.status.in_progress { background: oklch(88% 0.12 230); color: oklch(40% 0.25 230); font-weight: bold; margin-right: 2px;}
.status.completed   { background: var(--success);       color: white; font-weight: bold; margin-right: 2px;}
.status.cancelled   { background: var(--error);         color: white; font-weight: bold; margin-right: 2px;}

.status {
        padding: 7px 16px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

/* Base select look */
select[name="status"] {
    padding: 10px 14px;
    border-radius: 10px;
    border: none;
    background: var(--background);
    font-family: var(--body);
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    appearance: none;
    transition: 0.2s ease;
}
select[name="status"]:hover { filter: brightness(1.05); }
select[name="status"]:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(100,100,255,0.25);
}

/* STATUS BUTTON */
.status-btn {
    background: var(--primary);
    color: var(--background);

    font-family: var(--header);
    font-size: 15px;
    font-weight: 600;

    padding: 10px 18px;
    border-radius: 12px;
    border: none;

    cursor: pointer;
    transition: all 0.22s ease;

    display: inline-flex;
    align-items: center;
    gap: 6px;

    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

/* Hover */
.status-btn:hover {
    background: var(--secondary);
    color: var(--text);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.18);
}

/* Pressed */
.status-btn:active {
    transform: translateY(0px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.10);
}
/* NO GIGS */
.no-gigs {
    text-align: center;
    font-size: 22px;
    color: #888;
    padding: 100px 20px;
}

.gig-actions {
    margin-top: 18px;
    text-align: left;
}

.view-gig-btn {
    display: inline-block;
    padding: 5px 10px;
    background: var(--primary);
    color: var(--background);
    border-radius: 50px;
    font-family: var(--header);
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.22s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    width: 100%;
    text-align: center;
}

.view-gig-btn:hover {
    background: var(--secondary);
    color: var(--text);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.18);
}

.view-gig-btn:active {
    transform: translateY(0);
    box-shadow: 0 3px 10px rgba(0,0,0,0.10);
}

</style>
</head>

<body>

<?php include '../components/Header.php'; ?>

<?php if (isset($_GET['updated'])): ?>
<script>alert('Order status updated!');</script>
<?php endif; ?>

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
        <div class="gig">
            <img src="/images/gig-image-placeholder.jpg" alt="gig">

            <div class="gig-content">
                <div class="gig-title"><?= htmlspecialchars($gig['title']) ?></div>
                <div class="gig-desc">
                    <?= htmlspecialchars(substr($gig['description'] ?? 'No description.', 0, 120)) ?>...
                </div>

                <div class="gig-footer">
                    <div class="price">$<?= number_format($gig['price'], 2) ?></div>
                    <div>
                        <small>★ <?= $avg ?> (<?= $count ?>)</small><br>
                        <span class="delivery"><?= $gig['delivery_time'] ?></span>
                    </div>
                </div>

                <div class="gig-actions">
                    <a href="GigDetails.php?id=<?= $gig['id'] ?>" class="view-gig-btn">View Gig</a>
                </div>

                <?php
                $orders = $db->prepare("SELECT * FROM orders WHERE gig_id = ? ORDER BY created_at DESC");
                $orders->bindValue(1, $gig['id'], SQLITE3_INTEGER);
                $orderResult = $orders->execute();
                ?>

                <div class="orders-box">
                    <h4>Orders</h4>

                    <?php 
                    $hasOrders = false;
                    while ($order = $orderResult->fetchArray(SQLITE3_ASSOC)) { 
                        $hasOrders = true;
                    ?>
                    <div class="order-item">
                        <p><b>Order ID:</b> <?= $order['id'] ?></p>
                        <p><b>Buyer ID:</b> <?= $order['buyer_id'] ?></p>

                        <?php
                            $label = [
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'complete' => 'Complete'
                            ][$order['status']] ?? $order['status'];
                        ?>


                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

                            <select name="status">
                                <option value="pending"      <?= $order['status'] == 'pending' ? 'selected' : '' ?>>PENDING</option>
                                <option value="in_progress" <?= $order['status'] == 'in_progress' ? 'selected' : '' ?>>IN PROGRESS</option>
                                <option value="completed"   <?= $order['status'] == 'completed' ? 'selected' : '' ?>>COMPLETED</option>
                                <option value="cancelled"   <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>CANCELLED</option>
                            </select>

                            <button type="submit" name="update_status" class="status-btn">Update</button>
                        </form>
                    </div>
                    <?php } ?>

                    <?php if (!$hasOrders): ?>
                        <p style="color:#666; font-size:14px;">No orders yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if (!$found): ?>
            <div class="no-gigs">
                No gigs yet.<br><br>
                <a href="CreateGig.php" style="color:#6c5ce7;font-weight:bold;">
                    Create your first gig now!
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="../js/dropdown.js"></script>

<!-- STATUS DROPDOWN COLOR SCRIPT -->
<script>
document.querySelectorAll('select[name="status"]').forEach(select => {
    function updateStatusColor() {
        select.classList.remove("pending", "in_progress", "completed", "cancelled");
        select.classList.add("status");
        select.classList.add(select.value);
    }
    updateStatusColor();
    select.addEventListener("change", updateStatusColor);
});
</script>

</body>
</html>
