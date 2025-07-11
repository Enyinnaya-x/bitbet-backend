<?php

require_once '../config/db.php';
require_once '../utils/sanitize.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$response = ['success' => false]; // default

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = sanitizeString($_POST["username"]);
    $email = sanitizeString($_POST["email"]);
    $password = sanitizeString($_POST["password"]);
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

    if (empty($username) || empty($email) || empty($password)) {
        $response['message'] = "❌ All fields are required.";
        echo json_encode($response);
        exit;
    }

    $sql = "INSERT INTO users (email, password, user_name) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $email, $hashedpassword, $username);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            $response['success'] = true;
            $response['message'] = "✅ User registered successfully.";
        } else {
            $response['message'] = "❌ Insert error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = "❌ SQL prepare failed: " . $conn->error;
    }

    $conn->close();
}

echo json_encode($response);
