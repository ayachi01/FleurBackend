<?php
header("Content-Type: application/json");
include 'db.php'; // Ensure this file exists and connects properly

// Read form data from $_POST
$email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : null;
$password = isset($_POST["password"]) ? trim($_POST["password"]) : null;

// Validate input
if (!$email || !$password) {
    echo json_encode(["status" => "error", "message" => "Invalid input."]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format."]);
    exit();
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$stmt = $connection->prepare("INSERT INTO administrators (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Admin account created."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error creating admin: " . $stmt->error]);
}

$stmt->close();
$connection->close();
?>
