<?php
$servername = "localhost"; // Usually localhost
$username = "root"; // Your MySQL username (default XAMPP is root)
$password = ""; // Your MySQL password (default XAMPP is empty)
$dbname = "bet_bit";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
};

?>