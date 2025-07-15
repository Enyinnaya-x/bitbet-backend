<?php
session_start();
ob_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    $response['success'] = true;
}
ob_clean();
echo json_encode($response);
?>