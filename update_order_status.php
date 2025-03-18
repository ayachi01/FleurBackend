<?php
require 'api/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Validate and sanitize inputs
    $order_id = intval ($order_id);
    $status = $connection->real_escape_string($status);

    // Update the order status in the database
    $sql = "UPDATE cart SET status = '$status' WHERE id = $order_id";

    if ($connection->query($sql) === TRUE) {
        echo "Order status updated successfully";
    } else {
        echo "Error updating order status: " . $connection->error;
    }

    // Close the database connection
    $connection->close();

    // Redirect back to the orders page
    header("Location: orders.php");
    exit();
}
?>