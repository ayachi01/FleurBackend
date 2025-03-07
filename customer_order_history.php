<?php
require 'api/db.php';

$user_id = $_GET['user_id'] ?? 0; // Ensure user_id is provided in the request

$sql = "SELECT orders.id, flowers.name AS product_name, flowers.price, orders.quantity, (flowers.price * orders.quantity) AS total_price
        FROM orders
        JOIN flowers ON orders.flower_id = flowers.id
        WHERE orders.user_id = ?";

$stmt = $connection->prepare($sql);
if (!$stmt) {
    die("Query Error: " . $connection->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="customers.css">
</head>
<body>
    <div class="main-content">
        <h2>Order History</h2>
        <div class="order-container">
            <?php if (empty($orders)): ?>
                <p>No orders found for this customer.</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <h3><?php echo htmlspecialchars($order['product_name']); ?></h3>
                        <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
                        <p><strong>Price per item:</strong> ₱<?php echo htmlspecialchars($order['price']); ?></p>
                        <p><strong>Total Price:</strong> ₱<?php echo htmlspecialchars($order['total_price']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
