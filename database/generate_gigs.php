<?php
// GIGSTA - FULL SETUP 2025 (WITH DELIVERY TIME) - FINAL WORKING
$dbPath = __DIR__ . '/../database/gigsta.db';

echo "<pre style='font-family: monospace; background:#000; color:#0f0; padding:20px;'>";
echo "GIGSTA FULL SETUP + DELIVERY TIME (FINAL)\n\n";

$db = new SQLite3($dbPath);
$db->exec("PRAGMA foreign_keys = ON;");

function safeAlter($q) {
    global $db;
    try { $db->exec($q); echo "Added column: $q\n"; } catch(Exception $e) { /* already exists */ }
}

// ADD DELIVERY TIME COLUMN
safeAlter("ALTER TABLE gigs ADD COLUMN delivery_time TEXT DEFAULT '7 days'");

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('gigster','client')),
    onboarded INTEGER DEFAULT 0,
    is_pro INTEGER DEFAULT 0,
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

echo "Database ready with delivery_time column!\n";
echo "Run generate_gigs.php next!\n";
echo "</pre>";
?>