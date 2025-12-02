<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$port = 3307;
$dbname = "gigstadb";

$conn = mysqli_connect($host, $user, $pass, $dbname, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully!";
?>