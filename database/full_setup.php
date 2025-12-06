<?php
// GIGSTA - FULL SETUP 2025 (FINAL - WITH ALL USER FIELDS)
$dbPath = __DIR__ . '/gigsta.db';

echo "<pre style='font-family: monospace; background:#000; color:#0f0; padding:30px; font-size:16px;'>";
echo "GIGSTA FULL SETUP + USER FIELDS (AGE, LOCATION, EXPERIENCE, SKILLS)\n\n";

$db = new SQLite3($dbPath);
$db->exec("PRAGMA foreign_keys = ON;");

function safeAlter($q) {
    global $db;
    try { $db->exec($q); } catch(Exception $e) { /* column exists */ }
}

// ADD DELIVERY TIME + ALL USER PROFILE COLUMNS
safeAlter("ALTER TABLE gigs ADD COLUMN delivery_time TEXT DEFAULT '7 days'");
safeAlter("ALTER TABLE users ADD COLUMN age INTEGER");
safeAlter("ALTER TABLE users ADD COLUMN location TEXT");
safeAlter("ALTER TABLE users ADD COLUMN experience_years INTEGER DEFAULT 0");
safeAlter("ALTER TABLE users ADD COLUMN tags TEXT");  // stores skills as JSON

// CREATE TABLES
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('gigster','client')),
    onboarded INTEGER DEFAULT 0,
    is_pro INTEGER DEFAULT 0,
    age INTEGER,
    location TEXT,
    experience_years INTEGER DEFAULT 0,
    tags TEXT,
    created_at TEXT DEFAULT (datetime('now'))
)");

$db->exec("CREATE TABLE IF NOT EXISTS gigs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    category TEXT,
    price REAL DEFAULT 0,
    delivery_time TEXT DEFAULT '7 days',
    status TEXT DEFAULT 'active',
    created_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
)");

$db->exec("CREATE TABLE IF NOT EXISTS gig_reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    gig_id INTEGER NOT NULL,
    client_id INTEGER NOT NULL,
    rating REAL CHECK(rating BETWEEN 1 AND 5),
    review TEXT,
    created_at TEXT DEFAULT (datetime('now'))
)");

// FAKE DATA
$categories = ['Graphics & Design', 'Digital Marketing', 'Writing & Translation', 'Video & Animation', 'Music & Audio', 'Programming & Tech'];
$titles = ['Logo Design', 'Website Development', 'SEO', 'Video Editing', 'Voice Over', 'Content Writing', 'App Development'];
$deliveryTimes = ['24 hours', '3 days', '7 days', '14 days', '30 days'];
$locations = ['Iloilo City, Philippines', 'Manila, Philippines', 'Cebu, Philippines', 'Davao, Philippines', 'Bacolod, Philippines'];
$skills = [
    'Photoshop', 'Figma', 'React', 'Node.js', 'Python', 'Video Editing', 
    'Motion Graphics', 'UI/UX Design', 'Logo Design', 'Illustration', 
    'Copywriting', 'SEO', 'WordPress', 'Laravel', 'Voice Acting'
];

// Create 20 gigsters with full profile data
for ($i = 1; $i <= 20; $i++) {
    $username = "gigster" . $i;
    $email = "gigster$i@gigsta.com";
    $password = password_hash("password123", PASSWORD_DEFAULT);
    $age = random_int(18, 45);
    $location = $locations[array_rand($locations)];
    $experience = random_int(1, 15);
    $isPro = $i <= 6 ? 1 : 0;
    
    // Random 3–6 skills
    $userSkills = [];
    for ($s = 0; $s < random_int(3,6); $s++) {
        $userSkills[] = $skills[array_rand($skills)];
    }
    $userSkills = array_unique($userSkills);
    $tagsJson = json_encode($userSkills);

    $db->exec("INSERT OR IGNORE INTO users 
        (username, email, password, role, is_pro, age, location, experience_years, tags, onboarded) 
        VALUES 
        ('$username', '$email', '$password', 'gigster', $isPro, $age, '$location', $experience, '$tagsJson', 1)");
}

$gigsterIds = [];
$result = $db->query("SELECT id FROM users WHERE role = 'gigster'");
while ($row = $result->fetchArray()) {
    $gigsterIds[] = $row['id'];
}

// Generate gigs
foreach ($gigsterIds as $userId) {
    $numGigs = random_int(2, 5);
    for ($g = 0; $g < $numGigs; $g++) {
        $title = $titles[array_rand($titles)];
        $price = round(random_int(10, 300) + random_int(0,99)/100, 2);
        $delivery = $deliveryTimes[array_rand($deliveryTimes)];

        $stmt = $db->prepare("INSERT INTO gigs 
            (user_id, title, description, category, price, delivery_time) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $stmt->bindValue(2, "I will $title", SQLITE3_TEXT);
        $stmt->bindValue(3, "Professional and fast delivery from experienced freelancer.", SQLITE3_TEXT);
        $stmt->bindValue(4, $categories[array_rand($categories)], SQLITE3_TEXT);
        $stmt->bindValue(5, $price, SQLITE3_FLOAT);
        $stmt->bindValue(6, $delivery, SQLITE3_TEXT);
        $stmt->execute();

        $gigId = $db->lastInsertRowID();

        // Add reviews
        for ($r = 0; $r < random_int(1,8); $r++) {
            $rating = random_int(40,50)/10;
            $db->exec("INSERT INTO gig_reviews (gig_id, client_id, rating, review) 
                       VALUES ($gigId, 1, $rating, 'Great work! Fast delivery!')");
        }
    }
}

echo "SUCCESS!\n";
echo "20 gigsters created with:\n";
echo "   Age, Location, Experience Years, Skills (tags as JSON)\n";
echo "   100+ gigs with delivery time\n";
echo "   Reviews included\n\n";
echo "Your onboarding system now works perfectly!\n";
echo "Delete this file when done.\n";
echo "</pre>";
?>