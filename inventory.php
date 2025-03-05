<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'fleurhaven';
// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the flowers table
$sql = "SELECT * FROM flowers";
$result = $conn->query($sql);

// Initialize an array to hold the flower data
$flowers = [];

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch all results into the array
    while($row = $result->fetch_assoc()) {
        $flowers[] = $row;
    }
} else {
    echo "0 results";
}

// Close the database connection
$conn->close();
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
    <style>
  
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Fleur Haven</h2>
    <a href="home.html"><i class="fa-solid fa-house"></i> Home </a>
    <a href="orders.html"><i class="fa-solid fa-truck"></i> Orders</a>
    <a href="inventory.php"><i class="fa-solid fa-warehouse"></i>Inventory</a>
    <a href="customers.php"><i class="fa-solid fa-users"></i> Costumers</a>
    <a href="#"><i class="fa-solid fa-user"></i> Account</a>
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
            <th>Image </th>
            <th>Name</th>
            <th>Stock</th>
            <th>Price</th>
        </tr>
    </thead>
        <?php foreach ($flowers as $flower): ?>
        <tr>
            <td><?php echo htmlspecialchars($flower['id']); ?></td>
            <td><img src="<?php echo htmlspecialchars($flower['image_url']); ?>" alt="<?php echo htmlspecialchars($flower['name']); ?>"></td> 
            <td><?php echo htmlspecialchars($flower['name']); ?></td>
            <td><?php echo htmlspecialchars($flower['stock']); ?></td>
            <td><?php echo htmlspecialchars($flower['price']); ?></td>

        </tr>
        <?php endforeach; ?>
    </table>
</div>

</div>

</body>
</html>