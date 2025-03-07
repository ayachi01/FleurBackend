<?php
require 'api/db.php'; // Include database connection

// SQL query to fetch specific fields from the flowers table
$sql = "SELECT id, name, image_url, price, stock FROM flowers";
$result = $connection->query($sql);

// Initialize an array to hold the flower data
$flowers = [];

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch all results into the array
    while ($row = $result->fetch_assoc()) {
        $flowers[] = $row;
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
    
    <link rel="stylesheet" href="inventory.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="sidebar">
    <img src="assets/logo.png" alt="" class="signup-image">
    <a href="home.php"><i class="fa-solid fa-house"></i> Home </a>
    <a href="orders.html"><i class="fa-solid fa-truck"></i> Orders</a>
    <a href="inventory.php"><i class="fa-solid fa-warehouse"></i> Inventory</a>
    <a href="customers.php"><i class="fa-solid fa-users"></i> Customers</a>
    <a href="account.html"><i class="fa-solid fa-user"></i> Account</a>
    <button class="logout-button" onclick="logout()"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
</div>

<div class="main-content">
    <div class="card2">
        <h2>Inventory</h2>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flowers as $flower): ?>
                <tr>
                    <td><?php echo htmlspecialchars($flower['id']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($flower['image_url']); ?>" alt="<?php echo htmlspecialchars($flower['name']); ?>" width="100"></td> 
                    <td><?php echo htmlspecialchars($flower['name']); ?></td>
                    <td><?php echo htmlspecialchars($flower['stock']); ?></td>
                    <td>₱<?php echo number_format($flower['price'], 2); ?></td>
                    <td>
                        <?php 
                        if ($flower['stock'] > 0) {
                            echo "In Stock";
                        } else {
                            echo "Out of Stock";
                        }
                        ?>
                    </td>
                    <td><button class="edit"><i class="fa-solid fa-pen"></i>Edit</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>