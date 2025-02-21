<?php
session_start();
include 'db.php'; // Removed 'api/' since this file is already inside 'api/'

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!$connection) {
        die("Database connection error: " . mysqli_connect_error());
    }

    $stmt = $connection->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['email'] = $email;
            header("Location: ../welcome-page.html"); // Redirect after successful login
            exit();
        } else {
            $_SESSION['error'] = "Invalid password. Please try again.";
        }
    } else {
        $_SESSION['error'] = "No user found with that email address.";
    }

    $stmt->close();
    header("Location: ../login.php"); // Redirect back on failure
    exit();
} else {
    header("Location: ../login.php");
    exit();
}
?>
