<?php
 
try {
    $db = new SQLite3(__DIR__ . '/gigsta.db');
} catch (Exception $e) {
    die("Unable to connect to database: " . $e->getMessage());
}
 
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    \"role\" TEXT,
    onboarded INTEGER DEFAULT 0,
    created_at TEXT DEFAULT (datetime('now'))
)");

$db->exec("CREATE TABLE IF NOT EXISTS gigsters (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    name TEXT NOT NULL,
    email TEXT,
    skills TEXT,
    hourly_rate REAL,
    profile_url TEXT,
    created_at TEXT DEFAULT (datetime('now'))
)");

$count = (int)$db->querySingle("SELECT COUNT(*) FROM gigsters");
if ($count === 0) {
    $seed = [
        ['name'=>'Ana Lopez','email'=>'ana@gigsta.ph','skills'=>'Graphic Design,Logo,Branding','hourly'=>18.00,'profile'=>'/profiles/ana'],
        ['name'=>'Miguel Santos','email'=>'miguel@gigsta.ph','skills'=>'Frontend Dev,React,HTML/CSS','hourly'=>22.00,'profile'=>'/profiles/miguel'],
        ['name'=>'Carla Reyes','email'=>'carla@gigsta.ph','skills'=>'Copywriting,SEO','hourly'=>16.50,'profile'=>'/profiles/carla']
    ];

    $stmt = $db->prepare("INSERT INTO gigsters (name, email, skills, hourly_rate, profile_url) VALUES (:name,:email,:skills,:hourly,:profile)");
    foreach ($seed as $s) {
        $stmt->bindValue(':name', $s['name'], SQLITE3_TEXT);
        $stmt->bindValue(':email', $s['email'], SQLITE3_TEXT);
        $stmt->bindValue(':skills', $s['skills'], SQLITE3_TEXT);
        $stmt->bindValue(':hourly', $s['hourly'], SQLITE3_FLOAT);
        $stmt->bindValue(':profile', $s['profile'], SQLITE3_TEXT);
        $stmt->execute();
    }
}
?>