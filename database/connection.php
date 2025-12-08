<?php
// database/connection.php

try {
    $db = new SQLite3(__DIR__ . '/gigsta.db');
} catch (Exception $e) {
    die("Unable to connect to database: " . $e->getMessage());
}
?>