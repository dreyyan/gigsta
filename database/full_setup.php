<?php
// =============================================
// GIGSTA - Complete One-File Setup Script
// Run this ONCE to initialize database + fake data
// =============================================

$dbPath = __DIR__ . '/gigsta.db';

echo "<pre style='font-family: monospace; font-size: 14px;'>";
echo "=== GIGSTA FULL SETUP SCRIPT ===\n\n";

// Step 1: Connect or create database
try {
    $db = new SQLite3($dbPath);
    echo "Connected to SQLite database: $dbPath\n";
} catch (Exception $e) {
    die("Failed to connect/create database: " . $e->getMessage());
}

// Enable foreign keys
$db->exec("PRAGMA foreign_keys = ON;");

// Step 2: Create tables (idempotent with upgrades)

// Users table - with role and onboarded fields
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('gigster', 'client', NULL)),
    onboarded INTEGER DEFAULT 0,
    created_at TEXT DEFAULT (datetime('now'))
)");

// Gigs table
$db->exec("CREATE TABLE IF NOT EXISTS gigs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    category TEXT,
    price REAL DEFAULT 0,
    status TEXT DEFAULT 'active',
    created_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// Gig Reviews table
$db->exec("CREATE TABLE IF NOT EXISTS gig_reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    gig_id INTEGER NOT NULL,
    client_id INTEGER NOT NULL,
    rating REAL CHECK(rating >= 1 AND rating <= 5),
    review TEXT,
    created_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY(gig_id) REFERENCES gigs(id) ON DELETE CASCADE,
    FOREIGN KEY(client_id) REFERENCES users(id) ON DELETE CASCADE
)");

echo "All tables created/updated successfully!\n\n";

// Step 3: Helper functions
function randomString($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $str;
}

function randomEmail($username) {
    $domains = ['example.com', 'gigsta.test', 'mail.com', 'fiverrclone.org', 'freelance.dev'];
    return strtolower($username) . '@' . $domains[array_rand($domains)];
}

// Step 4: Generate 20 fake gigsters
$gigTitles = [
    'Professional Logo Design', 'Modern Website Development', 'SEO Optimization Service',
    'Social Media Marketing', 'Video Editing & Animation', 'Content Writing',
    'WordPress Website Setup', 'Mobile App UI Design', 'Voice Over Recording',
    'Business Card Design'
];

$categories = [
    'Graphics & Design', 'Programming & Tech', 'Digital Marketing',
    'Writing & Translation', 'Video & Animation', 'Music & Audio', 'Business'
];

$reviewTexts = [
    "Excellent work, highly recommend!",
    "Very professional and timely.",
    "Good communication, will hire again.",
    "The gig was delivered as expected.",
    "Outstanding quality and effort!",
    "Satisfied with the work delivered.",
    "Could be better, but overall good."
];

echo "Generating 20 fake gigsters...\n";

for ($i = 1; $i <= 20; $i++) {
    $baseName = randomString(6);
    $username = ucfirst($baseName) . random_int(10, 999);
    $email = randomEmail($username);
    $password = password_hash('password123', PASSWORD_DEFAULT);
    $role = 'gigster';

    // Insert gigster
    $stmt = $db->prepare("INSERT OR IGNORE INTO users (username, email, password, role, onboarded) 
                          VALUES (:username, :email, :password, :role, 1)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    $stmt->execute();

    $userId = $db->lastInsertRowID();
    if ($userId == 0) {
        // User already existed (unique email)
        $res = $db->query("SELECT id FROM users WHERE email = '$email' LIMIT 1");
        $row = $res->fetchArray(SQLITE3_ASSOC);
        $userId = $row['id'];
        echo "   → Gigster '$username' already exists, skipping gigs\n";
        continue;
    }

    // Create 1–3 random gigs for this gigster
    $numGigs = random_int(1, 3);
    for ($g = 0; $g < $numGigs; $g++) {
        $title = $gigTitles[array_rand($gigTitles)];
        $category = $categories[array_rand($categories)];
        $description = "I will $title for you quickly and professionally. High quality guaranteed!";
        $price = random_int(15, 250) + (random_int(0, 99) / 100);

        $stmt = $db->prepare("INSERT INTO gigs (user_id, title, description, category, price) 
                              VALUES (:user_id, :title, :description, :category, :price)");
        $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
        $stmt->bindValue(':title', $title, SQLITE3_TEXT);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':category', $category, SQLITE3_TEXT);
        $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
        $stmt->execute();

        $gigId = $db->lastInsertRowID();

        // Add 1–5 random reviews per gig
        $numReviews = random_int(1, 5);
        for ($r = 0; $r < $numReviews; $r++) {
            // Pick random client or create one if none exist
            $clientRes = $db->query("SELECT id FROM users WHERE role = 'client' ORDER BY RANDOM() LIMIT 1");
            $clientRow = $clientRes->fetchArray(SQLITE3_ASSOC);
            if (!$clientRow) {
                $clientUsername = 'Client' . random_int(100,999);
                $clientEmail = randomEmail($clientUsername);
                $clientPassword = password_hash('password123', PASSWORD_DEFAULT);
                $db->exec("INSERT INTO users (username,email,password,role,onboarded) 
                           VALUES ('$clientUsername','$clientEmail','$clientPassword','client',1)");
                $clientId = $db->lastInsertRowID();
            } else {
                $clientId = $clientRow['id'];
            }

            $rating = round(random_int(10,50)/10,1); // 1.0–5.0
            $review = $reviewTexts[array_rand($reviewTexts)];

            $stmt = $db->prepare("INSERT INTO gig_reviews (gig_id, client_id, rating, review) 
                                  VALUES (:gig_id, :client_id, :rating, :review)");
            $stmt->bindValue(':gig_id', $gigId, SQLITE3_INTEGER);
            $stmt->bindValue(':client_id', $clientId, SQLITE3_INTEGER);
            $stmt->bindValue(':rating', $rating, SQLITE3_FLOAT);
            $stmt->bindValue(':review', $review, SQLITE3_TEXT);
            $stmt->execute();
        }
    }

    echo "   ✓ Created gigster: $username ($email) with $numGigs gig(s) and reviews\n";
}

echo "\nSetup complete!\n";
echo "Database file: $dbPath\n";
echo "Default login for any gigster → username/email + password: password123\n";
echo "\nYou can now delete or rename this file for security.\n";
echo "</pre>";
?>