<?php
require 'api/db.php'; // Include database connection

// Default sorting parameters
$order_by = 'cart.id'; // Default sort by ID
$order = 'ASC'; // Default order

// Check if sorting parameters are set in the URL
if (isset($_GET['sort_by'])) {
    $order_by = $_GET['sort_by'];
}

if (isset($_GET['order'])) {
    $order = $_GET['order'];
}

// Validate the order_by and order parameters to prevent SQL injection
$valid_columns = ['cart.id', 'users.email', 'flowers.name', 'cart.quantity', 'cart.added_at', 'cart.status'];
if (!in_array($order_by, $valid_columns)) {
    $order_by = 'cart.id'; // Fallback to default
}

$valid_orders = ['ASC', 'DESC'];
if (!in_array($order, $valid_orders)) {
    $order = 'ASC'; // Fallback to default
}

// SQL query to fetch specific fields from the cart, flowers, and users tables
$sql = "SELECT cart.id, 
               users.email AS user_email, 
               cart.flower_id, 
               cart.quantity, 
               cart.added_at, 
               flowers.name AS flower_name, 
               flowers.image_url AS flower_image_url,
               cart.status 
        FROM cart 
        JOIN flowers ON cart.flower_id = flowers.id 
        JOIN users ON cart.user_id = users.id 
        ORDER BY $order_by $order";

$result = $connection->query($sql);

// Initialize an array to hold the cart data
$cart = [];

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch all results into the array
    while ($row = $result->fetch_assoc()) {
        $cart[] = $row;
    }
} else {
    echo "0 results";
}

// Close the database connection
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleur Haven</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&family=Birthstone&family=Ephesis&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="script.js" defer></script> <!-- Include the JavaScript file -->
</head>
<body>

<div class="sidebar">
    <img src="assets/logo.png" alt="" class="signup-image">
    <a href="home.php"><i class="fa-solid fa-house"></i> Home </a>
    <a href="orders.php"><i class="fa-solid fa-truck"></i> Orders</a>
    <a href="inventory.php"><i class="fa-solid fa-warehouse"></i> Inventory</a>
    <a href="customers.php"><i class="fa-solid fa-users"></i> Customers</a>
    <a href="account.html"><i class="fa-solid fa-user"></i> Account</a>
    <button class="logout-button" onclick="confirmLogout()"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
</div>

<div class="main-content">
    <div class="card2">
        <h2>Orders</h2>
    </div>

    <div class="card">
        <div class="order-history">
            <form method="GET" action="">
                <label for="sort_by">Sort By:</label>
                <select name="sort_by" id="sort_by" onchange="this.form.submit()">
                    <option value="cart.id" <?php echo ($order_by == 'cart.id') ? 'selected' : ''; ?>>ID</option>
                    <option value="users.email" <?php echo ($order_by == 'users.email') ? 'selected' : ''; ?>>User  Email</option>
                    <option value="flowers.name" <?php echo ($order_by == 'flowers.name ') ? 'selected' : ''; ?>>Flower</option>
                    <option value="cart.quantity" <?php echo ($order_by == 'cart.quantity') ? 'selected' : ''; ?>>Quantity</option>
                    <option value="cart.added_at" <?php echo ($order_by == 'cart.added_at') ? 'selected' : ''; ?>>Date Added</option>
                    <option value="cart.status" <?php echo ($order_by == 'cart.status') ? 'selected' : ''; ?>>Status</option>
                </select>
                <input type="hidden" name="order" value="<?php echo ($order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                <button type="submit" name="order" value="ASC">Sort Ascending</button>
                <button type="submit" name="order" value="DESC">Sort Descending</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Email</th>
                        <th>Flower</th>
                        <th>Flower Image</th>
                        <th>Quantity</th>
                        <th>Date Added</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                        <td><?php echo htmlspecialchars($item['user_email']); ?></td>
                        <td><?php echo htmlspecialchars($item['flower_name']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($item['flower_image_url']); ?>" alt="<?php echo htmlspecialchars($item['flower_name']); ?>" width="100"></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($item['added_at']); ?></td>
                        <td><?php echo htmlspecialchars($item['status']); ?></td>
                        <td>
                            <button class="edit" onclick="openEditModal(<?php echo htmlspecialchars($item['id']); ?>)">Edit</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>


<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Order Status</h2>
        <form id="editForm" method="POST" action="update_order_status.php">
            <input type="hidden" name="order_id" id="order_id">
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="Pending">Pending</option>
                <option value="Preparing">Preparing</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
                <option value="Cancelled">Cancelled</option>
                
            </select>
            <button type="submit">Update Status</button>
        </form>
    </div>
</div>

<script>

function openEditModal(orderId) {
    document.getElementById('order_id').value = orderId;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
</body>
</html>