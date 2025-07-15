<?php
// ✅ Allow session cookie from cross-origin
session_set_cookie_params([
    'samesite' => 'None',
    'secure' => true
]);

session_start();

// ✅ Allow only your frontend
$allowed_origins = [
    'https://bitbet.netlify.app',
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Credentials: true");
}

header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ✅ Debugging (dev only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Connect to DB
require_once '../config/db.php';

$response = ['success' => false];

// ✅ Check if user is logged in
if (!isset($_SESSION['uid'])) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

$uid = $_SESSION['uid'];

// ✅ Fetch balance
$sql = "SELECT balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $response['success'] = true;
    $response['data'] = $row['balance'];
} else {
    $response['message'] = 'Failed to fetch balance';
}

echo json_encode($response);
?>