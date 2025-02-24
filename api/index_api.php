<?php
session_start();
include 'db.php'; // Ensure this file contains the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!$connection) {
        die("Database connection error: " . mysqli_connect_error());
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $connection->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Successful login
            $_SESSION['email'] = $email;
            header("Location: ../home.html"); // Redirect to home.html
            exit();
        } else {
            // Invalid password
            $_SESSION['error'] = "Invalid password. Please try again.";
        }
    } else {
        // No user found
        $_SESSION['error'] = "No user found with that email address.";
    }

    $stmt->close();
    // Redirect back to the login page with an error
    header("Location: ../index.php");
    exit();
} else {
    // If the request method is not POST, redirect to the login page
    header("Location: ../index.php");
    exit();
}
?>