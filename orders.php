<?php
require 'api/db.php'; // Include database connection

// SQL query to fetch specific fields from the cart, flowers, and users tables
$sql = "SELECT cart.id, 
               users.email AS user_email, 
               cart.flower_id, 
               cart.quantity, 
               cart.added_at, 
               flowers.name AS flower_name, 
               flowers.image_url AS flower_image_url 
        FROM cart 
        JOIN flowers ON cart.flower_id = flowers.id 
        JOIN users ON cart.user_id = users.id";

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
</head>
<body>

<div class="sidebar">
    <img src="assets/logo.png" alt="" class="signup-image">
    <a href="home.php"><i class="fa-solid fa-house"></i> Home </a>
    <a href="orders.php"><i class="fa-solid fa-truck"></i> Orders</a>
    <a href="inventory.php"><i class="fa-solid fa-warehouse"></i> Inventory</a>
    <a href="customers.php"><i class="fa-solid fa-users"></i> Customers</a>
    <a href="account.html"><i class="fa-solid fa-user"></i> Account</a>
    <button class="logout-button" onclick="logout()"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
</div>

<div class="main-content">
    <div class="card2">
        <h2>Orders</h2>
    </div>

    <div class="card">
        <div class="order-history">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Email</th>
                    <th>Flower</th>
                    <th>Flower Image</th> 
                    <th>Quantity</th>
                    <th>Date Added</th>
                    <th>Order Status </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                    <td><?php echo htmlspecialchars($item['user_email']); ?></td> 
                    <td><?php echo htmlspecialchars($item['flower_name']); ?></td> 
                    <td><img src="<?php echo htmlspecialchars($item['flower_image_url']); ?>" alt="<?php echo htmlspecialchars($item['flower_name']); ?>" width="100"></td> <!-- Display flower image -->
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($item['added_at']); ?></td>
                    <td><button class="add_status"> Edit </button> </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    
</div>

</body>
</html>