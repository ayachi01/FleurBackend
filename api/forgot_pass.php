<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $connection->prepare("SELECT id FROM administrators WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a secure reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); 

        // Store reset token in database
        $stmt = $connection->prepare("UPDATE administrators SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Call send_reset_email.php
        header("Location: send_reset_email.php?email=$email&token=$token");
        exit();
    } else {
        $_SESSION['error'] = "No account found with that email.";
        header("Location: ../forgotpass.php");
        exit();
    }
}
?>
