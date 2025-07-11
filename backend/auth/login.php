<?php
session_start();
require_once '../config/db.php';
require_once "../utils/sanitize.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeString($_POST["email"]);
    $password = sanitizeString($_POST["password"]);

    if (empty($email) || empty($password)) {
        $response['message'] = "❌ Email and password are required.";
        echo json_encode($response);
        exit;
    }

    $sql = "SELECT id, password, user_name FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $user['user_name'];

                $response['success'] = true;
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

echo json_encode($response);