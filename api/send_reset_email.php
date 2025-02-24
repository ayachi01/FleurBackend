<?php
session_start(); // Ensure session is started
require __DIR__ . '/../vendor/autoload.php'; // Ensure correct path
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['email'])) {
        $_SESSION['error'] = "Invalid request.";
        header("Location: ../forgotpass.php");
        exit();
    }

    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $_SESSION['error'] = "Invalid email address.";
        header("Location: ../forgotpass.php");
        exit();
    }

    // Check if email exists & get admin ID
    $stmt = $connection->prepare("SELECT id FROM administrators WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Database error.";
        header("Location: ../forgotpass.php");
        exit();
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    if (!$admin) {
        $_SESSION['error'] = "No account found with that email.";
        header("Location: ../forgotpass.php");
        exit();
    }

    $admin_id = $admin['id'];

    // Generate secure reset token
    $token = bin2hex(random_bytes(32));

    // Store reset token in password_resets table
    $stmt = $connection->prepare("INSERT INTO password_resets (admin_id, token, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
    if (!$stmt) {
        $_SESSION['error'] = "Database error.";
        header("Location: ../forgotpass.php");
        exit();
    }

    $stmt->bind_param("is", $admin_id, $token);
    if (!$stmt->execute()) {
        $_SESSION['error'] = "Failed to store reset token.";
        header("Location: ../forgotpass.php");
        exit();
    }
    $stmt->close();

    // Reset link
    $reset_link = "http://localhost/FleurBackend/resetpass.php?token=$token";

    // Send email using PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ryanquinto1029@gmail.com';  // Your email
        $mail->Password = 'rgih zgky vlnn onzl';  // Use App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email settings
        $mail->setFrom('no-reply@fleurhaven.com', 'Fleur Haven Support');
        $mail->addAddress($email);
        $mail->Subject = "Password Reset Request";
        $mail->Body = "Click the link below to reset your password:\n\n$reset_link\n\nThis link will expire in 1 hour.";

        // Send email
        if ($mail->send()) {
            $_SESSION['success'] = "Password reset link sent to your email.";
        } else {
            $_SESSION['error'] = "Failed to send email.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Email Error: " . $mail->ErrorInfo;
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

// Redirect to forgot password page
header("Location: ../forgotpass.php");
exit();
?>
