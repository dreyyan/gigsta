<?php
// =============================================
// GIGSTA - Complete One-File Setup Script (2025 FIXED VERSION)
// Run this ONCE → creates DB + 20 gigsters + gigs + reviews + tags
// =============================================

$dbPath = __DIR__ . '/gigsta.db';

echo "<pre style='font-family: monospace; font-size: 14px; background:#000; color:#0f0; padding:20px;'>";
echo "GIGSTA FULL SETUP SCRIPT (Dec 2025) \n\n";

// Step 1: Connect or create database
try {
    $db = new SQLite3($dbPath);
    echo "Connected to database: $dbPath\n";
} catch (Exception $e) {
    die("Failed to connect/create database: " . $e->getMessage());
}

$db->exec("PRAGMA foreign_keys = ON;");

// Step 2: SAFELY add all required columns (won't crash if they exist)
function safeAlter($query) {
    global $db;
    try { $db->exec($query); } catch (Exception $e) { /* column exists */ }
}

safeAlter("ALTER TABLE users ADD COLUMN role TEXT CHECK(role IN ('gigster','client'))");
safeAlter("ALTER TABLE users ADD COLUMN onboarded INTEGER DEFAULT 0");
safeAlter("ALTER TABLE users ADD COLUMN is_pro INTEGER DEFAULT 0");
safeAlter("ALTER TABLE users ADD COLUMN age INTEGER");
safeAlter("ALTER TABLE users ADD COLUMN location TEXT");
safeAlter("ALTER TABLE users ADD COLUMN experience_years INTEGER");
safeAlter("ALTER TABLE users ADD COLUMN created_at TEXT DEFAULT (datetime('now'))");

// Step 3: Create tables (idempotent)
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT,
    onboarded INTEGER DEFAULT 0,
    is_pro INTEGER DEFAULT 0,
    age INTEGER,
    location TEXT,
    experience_years INTEGER,
    created_at TEXT DEFAULT (datetime('now'))
)");

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

// Tags system
$db->exec("CREATE TABLE IF NOT EXISTS tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
)");

$db->exec("CREATE TABLE IF NOT EXISTS user_tags (
    user_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, tag_id),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY(tag_id) REFERENCES tags(id) ON DELETE CASCADE
)");

$db->exec("CREATE TABLE IF NOT EXISTS auth_tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    selector TEXT NOT NULL UNIQUE,
    token TEXT NOT NULL,
    expires INTEGER NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
)");

echo "All tables & columns created/upgraded safely.\n\n";

// Step 4: Helper functions
function randomString($len = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $str = '';
    for ($i = 0; $i < $len; $i++) $str .= $chars[random_int(0, strlen($chars)-1)];
    return $str;
}

function randomEmail($name) {
    $domains = ['gmail.com','yahoo.com','outlook.com','gigsta.test','mail.com'];
    return strtolower($name) . random_int(10,999) . '@' . $domains[array_rand($domains)];
}

// Step 5: Generate 20 fake gigsters
$gigTitles = ['Logo Design','Website Development','Video Editing','Social Media Marketing','Music Production','Voice Over','SEO','Animation','Writing','Graphic Design'];
$categories = ['Design','Development','Video','Marketing','Music','Writing','Business'];
$allTags = ['Producer','Rapper','Developer','Designer','Video Editor','Writer','Marketer','Photographer','Voice Actor','Animator'];
$reviewTexts = ["Great work! Delivered fast!", "Amazing quality, highly recommend!", "Super professional!", "Exactly what I needed!", "Will hire again!"];

echo "Generating 20 gigsters with gigs, reviews, and tags...\n";

for ($i = 1; $i <= 20; $i++) {
    $base = randomString(6);
    $username = ucfirst($base) . random_int(10, 999);
    $email = randomEmail($username);
    $password = password_hash('password123', PASSWORD_DEFAULT);
    $role = 'gigster';
    $isPro = ($i <= 5) ? 1 : 0; // First 5 are Pro

    $stmt = $db->prepare("INSERT OR IGNORE INTO users (username,email,password,role,onboarded,is_pro,age,location,experience_years) 
                          VALUES (:u,:e,:p,:r,1,:pro, :age, :loc, :exp)");
    $stmt->bindValue(':u', $username, SQLITE3_TEXT);
    $stmt->bindValue(':e', $email, SQLITE3_TEXT);
    $stmt->bindValue(':p', $password, SQLITE3_TEXT);
    $stmt->bindValue(':r', $role, SQLITE3_TEXT);
    $stmt->bindValue(':pro', $isPro, SQLITE3_INTEGER);
    $stmt->bindValue(':age', random_int(18, 45), SQLITE3_INTEGER);
    $stmt->bindValue(':loc', 'Iloilo City, Philippines', SQLITE3_TEXT);
    $stmt->bindValue(':exp', random_int(1, 15), SQLITE3_INTEGER);
    $stmt->execute();

    $userId = $db->lastInsertRowID();
    if (!$userId) {
        $res = $db->query("SELECT id FROM users WHERE email='$email'")->fetchArray();
        $userId = $res['id'];
    }

    // Add random tags
    $numTags = random_int(1, 4);
    shuffle($allTags);
    for ($t = 0; $t < $numTags; $t++) {
        $tag = $allTags[$t];
        $db->exec("INSERT OR IGNORE INTO tags (name) VALUES ('$tag')");
        $tagId = $db->query("SELECT id FROM tags WHERE name='$tag'")->fetchArray()['id'];
        $db->exec("INSERT OR IGNORE INTO user_tags (user_id, tag_id) VALUES ($userId, $tagId)");
    }

    // Create 1–3 gigs
    $numGigs = random_int(1, 3);
    for ($g = 0; $g < $numGigs; $g++) {
        $title = $gigTitles[array_rand($gigTitles)];
        $desc = "Professional $title service with fast delivery and revisions.";
        $price = random_int(20, 300) + (random_int(0,99)/100);

        $stmt = $db->prepare("INSERT INTO gigs (user_id,title,description,category,price) 
                              VALUES (:uid,:t,:d,:c,:p)");
        $stmt->bindValue(':uid', $userId, SQLITE3_INTEGER);
        $stmt->bindValue(':t', $title, SQLITE3_TEXT);
        $stmt->bindValue(':d', $desc, SQLITE3_TEXT);
        $stmt->bindValue(':c', $categories[array_rand($categories)], SQLITE3_TEXT);
        $stmt->bindValue(':p', $price, SQLITE3_FLOAT);
        $stmt->execute();
        $gigId = $db->lastInsertRowID();

        // Add 0–5 fake reviews
        $numReviews = random_int(0, 5);
        for ($r = 0; $r < $numReviews; $r++) {
            $clientRes = $db->query("SELECT id FROM users WHERE role='client' ORDER BY RANDOM() LIMIT 1")->fetchArray();
            $clientId = $clientRes ? $clientRes['id'] : 1;

            $rating = round(random_int(35,50)/10, 1);
            $review = $reviewTexts[array_rand($reviewTexts)];

            $db->prepare("INSERT INTO gig_reviews (gig_id, client_id, rating, review) 
                          VALUES (?, ?, ?, ?)")->bindValue(1, $gigId, SQLITE3_INTEGER)
                                           ->bindValue(2, $clientId, SQLITE3_INTEGER)
                                           ->bindValue(3, $rating, SQLITE3_FLOAT)
                                           ->bindValue(4, $review, SQLITE3_TEXT)
                                           ->execute();
        }
    }

    echo "Created gigster: $username ($email) | Pro: " . ($isPro ? 'Yes' : 'No') . " | $numGigs gigs\n";
}

echo "\nSETUP COMPLETE!\n";
echo "Database: $dbPath\n";
echo "Login: any gigster email + password123\n";
echo "You can now delete this file.\n";
echo "</pre>";
?>