<?php
$conn = mysqli_connect("localhost", "root", "", "gigstadb", 3307);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully!";
?>
