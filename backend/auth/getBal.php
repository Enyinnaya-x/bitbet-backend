<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/db.php";

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$userId = $_SESSION['uid'];

$result = $conn->query("SELECT balance FROM users WHERE id = $userId");

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(["success" => true, "data" => $row['balance']]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to fetch balance"]);
}
