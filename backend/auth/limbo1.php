<?php
session_set_cookie_params(['samesite' => 'None', 'secure' => true]);
session_start();

$allowed_origins = ['https://bitbet.netlify.app'];
if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Credentials: true");
}
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/db.php';

$response = ['success' => false];
if (!isset($_SESSION['uid'])) {
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit;
}

$uid = $_SESSION['uid'];
$input = json_decode(file_get_contents("php://input"), true);
$amount = floatval($input['amount'] ?? 0);
$guess = intval($input['guess'] ?? 0);

if ($amount <= 0 || $guess < 1 || $guess > 10) {
    $response['message'] = 'Invalid amount or guess';
    echo json_encode($response);
    exit;
}

// Generate number
$randomNum = rand(1, 10);

// Get user bitbucks
$stmt = $conn->prepare("SELECT bitbucks FROM users WHERE id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$current = floatval($user['bitbucks']);

if ($current < $amount) {
    $response['message'] = 'Insufficient bitbucks';
    echo json_encode($response);
    exit;
}

// Deduct bet
$current -= $amount;

// Win condition
$win = false;
$payout = 0;
if ($guess === $randomNum) {
    $win = true;
    $payout = $amount * 10; // example: x10 reward
    $current += $payout;
}

// Update user bitbucks
$update = $conn->prepare("UPDATE users SET bitbucks = ? WHERE id = ?");
$update->bind_param("di", $current, $uid);
$update->execute();

$response['success'] = true;
$response['random'] = $randomNum;
$response['win'] = $win;
$response['payout'] = $win ? $payout : 0;
$response['new_balance'] = $current;
echo json_encode($response);
?>
