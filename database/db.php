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
    password TEXT NOT NULL
)");
?>