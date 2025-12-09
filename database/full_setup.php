<?php
// FORCE ERROR REPORTING (add this at the very top!)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Also log errors to a file so you can see them even if output is blocked
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

// GIGSTA - FULL SETUP 2025 (FINAL - WITH REALISTIC USERNAMES, TITLES & DESCRIPTIONS)
$dbPath = __DIR__ . '/gigsta.db';

echo "<pre style='font-family: monospace; background:#000; color:#0f0; padding:30px; font-size:16px;'>";
echo "GIGSTA FULL SETUP + REALISTIC DATA (2025)\n\n";

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

// REALISTIC DATA POOL
$firstNames = ['Mikaela', 'Rafael', 'Camille', 'Jolo', 'Ariana', 'Diego', 'Sofia', 'Lance', 'Isabella', 'Marco', 'Natasha', 'Enzo', 'Leona', 'Gabriel', 'Clarisse', 'Vincent', 'Andrea', 'Paolo', 'Juliana', 'Carlos'];
$lastNames  = ['Cruz', 'Reyes', 'Santos', 'Lim', 'Garcia', 'Tan', 'Ong', 'Mendoza', 'Villanueva', 'Rivera', 'Torres', 'Castillo', 'De Guzman', 'Aquino', 'Fernandez', 'Ramos', 'Sy', 'Chua', 'Go', 'Yap'];

$locations = ['Iloilo City, Philippines', 'Manila, Philippines', 'Cebu City, Philippines', 'Davao City, Philippines', 'Bacolod City, Philippines', 'Quezon City, Philippines', 'Makati City, Philippines', 'Taguig City, Philippines'];

$skills = [
    'Photoshop', 'Figma', 'Adobe Illustrator', 'React', 'Node.js', 'Python', 'Laravel', 
    'WordPress', 'UI/UX Design', 'Logo Design', 'Brand Identity', 'Video Editing', 
    'Motion Graphics', 'After Effects', 'Premiere Pro', 'Copywriting', 'SEO', 
    'Social Media Management', 'Voice Over', 'Illustration', '3D Modeling', 'Blender'
];

$realisticGigs = [
    // Graphics & Design
    ["title" => "I will design a modern minimalist logo for your brand",           "desc" => "Clean, timeless logo with unlimited revisions until you're 100% happy. Perfect for startups and small businesses.", "cat" => "Graphics & Design"],
    ["title" => "I will create stunning brand identity and style guide",         "desc" => "Full branding package: logo, color palette, typography, business cards, letterhead + brand guidelines.", "cat" => "Graphics & Design"],
    ["title" => "I will design eye-catching social media posts and stories",     "desc" => "10 custom Instagram/Facebook posts + stories templates tailored to your brand voice.", "cat" => "Graphics & Design"],
    ["title" => "I will illustrate custom characters or mascots",                 "desc" => "Unique hand-drawn or vector characters for your app, game, or merchandise.", "cat" => "Graphics & Design"],

    // Programming & Tech
    ["title" => "I will build a custom WordPress website from scratch",           "desc" => "Fast, responsive, SEO-friendly WordPress site with premium theme and plugins.", "cat" => "Programming & Tech"],
    ["title" => "I will develop a modern React or Next.js web application",      "desc" => "Full-stack React/Next.js app with clean code, responsive design and API integration.", "cat" => "Programming & Tech"],
    ["title" => "I will create a professional Laravel backend API",               "desc" => "Secure RESTful API with authentication, documentation (Postman/Swagger), and database design.", "cat" => "Programming & Tech"],

    // Digital маркетинге
    ["title" => "I will setup and manage your Facebook & Instagram ads campaign", "desc" => "Complete ad account setup, audience research, creative copy + images, and daily optimization for 30 days.", "cat" => "Digital Marketing"],
    ["title" => "I will do complete SEO audit and on-page optimization",         "desc" => "Technical SEO audit, keyword research, meta tags, speed optimization + detailed report.", "cat" => "Digital Marketing"],

    // Video & Animation
    ["title" => "I will edit a professional YouTube video with effects",          "desc" => "Cinematic editing, color grading, sound design, transitions, and subtitles.", "cat" => "Video & Animation"],
    ["title" => "I will create an animated explainer video for your product",     "desc" => "2D animated explainer (up to 60 sec) with script, storyboard, voiceover, and music.", "cat" => "Video & Animation"],

    // Writing & Translation
    ["title" => "I will write engaging blog posts or articles (1000 words)",     "desc" => "Well-researched, SEO-optimized articles that drive traffic and engagement.", "cat" => "Writing & Translation"],
    ["title" => "I will be your professional English-Filipino translator",        "desc" => "Accurate translation of documents, websites, apps, or marketing materials.", "cat" => "Writing & Translation"],

    // Music & Audio
    ["title" => "I will record a warm professional Filipino/English voice over", "desc" => "Studio-quality voice over for commercials, e-learning, IVR, or YouTube intros.", "cat" => "Music & Audio"],
    ["title" => "I will produce a catchy jingle or background music for your brand", "desc" => "Original royalty-free music tailored to your brand personality.", "cat" => "Music & Audio"],
];

$deliveryTimes = ['24 hours', '3 days', '5 days', '7 days', '10 days', '14 days'];

// CREATE 20 REALISTIC GIGSTERS
$db->exec("DELETE FROM gig_reviews;");
$db->exec("DELETE FROM gigs;");
$db->exec("DELETE FROM users WHERE role = 'gigster';");   // ← This fixes the "no data" problem

for ($i = 0; $i < 20; $i++) {
    $first = $firstNames[array_rand($firstNames)];
    $last  = $lastNames[array_rand($lastNames)];
    $username = strtolower($first . '_' . $last);
    $email = strtolower($first . '.' . $last . "@gmail.com");
    $password = password_hash("password123", PASSWORD_DEFAULT);
    $age = random_int(20, 42);
    $location = $locations[array_rand($locations)];
    $experience = random_int(2, 14);
    $isPro = $i < 6 ? 1 : 0;

    $shuffled = $skills;
    shuffle($shuffled);
    $userSkills = array_slice($shuffled, 0, random_int(3, 6));
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

// Generate realistic gigs
foreach ($gigsterIds as $userId) {
    $numGigs = random_int(2, 50);
    
    // FIXED LINE — was $g-- (decrement) → changed to $g++ (increment)
    for ($g = 0; $g < $numGigs; $g++) {
        $gig = $realisticGigs[array_rand($realisticGigs)];
        $price = random_int(15, 280);
        $delivery = $deliveryTimes[array_rand($deliveryTimes)];

        $stmt = $db->prepare("INSERT INTO gigs 
            (user_id, title, description, category, price, delivery_time) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $gig['title'], SQLITE3_TEXT);
        $stmt->bindValue(3, $gig['desc'], SQLITE3_TEXT);
        $stmt->bindValue(4, $gig['cat'], SQLITE3_TEXT);
        $stmt->bindValue(5, $price . '.00', SQLITE3_FLOAT);
        $stmt->bindValue(6, $delivery, SQLITE3_TEXT);
        $stmt->execute();

        $gigId = $db->lastInsertRowID();

        // Add random reviews
        for ($r = 0; $r < random_int(1, 9); $r++) {
            $rating = random_int(42,50)/10;
            $reviews = [
                'Amazing work! Delivered ahead of time and exceeded expectations.',
                'Super professional and easy to communicate with. Will hire again!',
                'High quality delivery, exactly what I needed. Thank you!',
                'Fast turnaround and great attention to detail.',
                'Best freelancer I\'ve worked with on this platform!'
            ];
            $review = $reviews[array_rand($reviews)];
            $db->exec("INSERT INTO gig_reviews (gig_id, client_id, rating, review) 
                       VALUES ($gigId, 1, $rating, '$review')");
        }
    }
}

echo "SUCCESS!\n";
echo "20 gigsters with REALISTIC usernames and full profiles created\n";
echo "Realistic gig titles & descriptions\n";
echo "Natural-looking reviews\n";
echo "Database ready for 2025 launch!\n\n";
echo "Delete this file when done.\n";
echo "</pre>";
?>