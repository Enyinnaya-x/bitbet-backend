<?php
session_start();

// 🔹 Allow only these frontend origins
$allowed_origins = [
    'https://bitbet.netlify.app',
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Credentials: true");
}

// 🔹 CORS preflight headers
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 🔹 Debugging (for dev only, remove in prod)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔹 Load required files
require_once '../config/db.php';
require_once '../utils/sanitize.php';

// 🔹 Default response
$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 🔹 Accept raw JSON if sent
    $email = sanitizeString($_POST['email']);
    $password = sanitizeString($_POST['password']);

    if (empty($email) || empty($password)) {
        $response['message'] = "❌ Email and password are required.";
        echo json_encode($response);
        exit;
    }

    // 🔹 Prepare and execute login query
    $sql = "SELECT user_id, password, user_name FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // ✅ Set session variables
                $_SESSION['uid'] = $user['user_id'];
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $user['user_name'];

                $response['success'] = true;
                $response['message'] = "Login successful";
                $response['username'] = $user['user_name'];
            } else {
                $response['message'] = "❌ Incorrect password.";
            }
        } else {
            $response['message'] = "❌ No user found with this email.";
        }

        $stmt->close();
    } else {
        $response['message'] = "❌ Failed to prepare login statement.";
    }

    $conn->close();
}

// 🔹 Final JSON response
echo json_encode($response);
