<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session completely
session_destroy();

// Redirect back to homepage or login page
header("Location: /../pages/Login.php");
exit;