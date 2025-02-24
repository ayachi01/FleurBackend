<?php
require 'db.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (empty($token) || empty($new_password)) {
        echo json_encode(["status" => "error", "message" => "Token and new password are required."]);
        exit;
    }

    // Check if the password_resets table exists
    $table_check = $connection->query("SHOW TABLES LIKE 'password_resets'");
    if ($table_check->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Password reset functionality is unavailable."]);
        exit;
    }

    // Verify token and check expiration
    $query = $connection->prepare("SELECT admin_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $query->bind_param("s", $token);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
        exit;
    }

    $user = $result->fetch_assoc();
    $admin_id = $user['admin_id'];

    // Update password securely
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $connection->prepare("UPDATE administrators SET password = ? WHERE id = ?");
    $update->bind_param("si", $hashed_password, $admin_id);
    
    if ($update->execute()) {
        // Delete token after successful password update
        $delete = $connection->prepare("DELETE FROM password_resets WHERE token = ?");
        $delete->bind_param("s", $token);
        $delete->execute();

        echo json_encode(["status" => "success", "message" => "Password has been reset successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update password."]);
    }
}
?>
