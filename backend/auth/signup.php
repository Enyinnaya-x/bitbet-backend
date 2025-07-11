<?php
require_once '../config/db.php';
require_once "../utils/sanitize.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

header('Content-Type: application/json'); // ✅ Always send this FIRST

$response = ['success' => false]; // default


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = sanitizeString($_POST["username"]);
    $email = sanitizeString($_POST["email"]);
    $password = sanitizeString($_POST["password"]);
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (email, password, user_name) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $email, $hashedpassword, $username);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['message'] = "❌ Error inserting data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = "❌ Failed to prepare statement: " . $conn->error;
    }

    $conn->close();
}
echo json_encode($response);
?>
