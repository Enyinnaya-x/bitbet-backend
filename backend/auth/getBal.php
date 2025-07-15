<?php
session_set_cookie_params([
    'samesite' => 'None',
    'secure' => true
]);
session_start();

// CORS dynamic origin support
$allowed_origins = [
    'http://localhost:5173',
    'https://bitbet.netlify.app',
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}

header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/db.php";

// Use session user ID (if logged in)
if (!isset($_SESSION['uid'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$userId = $_SESSION['uid'];

$result = $conn->query("SELECT balance FROM users WHERE user_id = $userId");

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(["success" => true, "data" => $row['balance']]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to fetch balance"]);
}
