
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

    // SQL query to fetch data from the users table
    $sql = "SELECT * FROM `users`"; // Adjust the fields as necessary
    $result = $conn->query($sql);

    // Initialize an array to hold the user data
    $users = [];

     // Check if there are results
    if ($result->num_rows > 0) {
        // Fetch all results into the array
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
            }
        } else {
                echo "0 results";
        }
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
    
    <link rel="stylesheet" href="customers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
   
</head>
<body>
    <div class="sidebar">
        <h2>Fleur Haven</h2>
        <a href="home.html"><i class="fa-solid fa-house"></i> Home </a>
        <a href="orders.html"><i class="fa-solid fa-truck"></i> Orders</a>
        <a href="inventory.php"><i class="fa-solid fa-warehouse"></i>Inventory</a>
        <a href="customers.php"><i class="fa-solid fa-users"></i> Costumers</a>
        <a href="account.html"><i class="fa-solid fa-user"></i> Account</a>
        
    </div>

    <div class="main-content">
        <div class="card2">
            <h2> Costumers </h2> 
        </div>
        
    <div class="card">
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Password</th>
            <th>Address</th>
       
        </tr>
    </thead>
        <?php foreach ($users as $users): ?>
        <tr>
            <td><?php echo htmlspecialchars($users['id']); ?></td>
            <td><?php echo htmlspecialchars($users['email']); ?></td>
            <td><?php echo htmlspecialchars($users['password']); ?></td>
            <td><?php echo htmlspecialchars($users['address']);?> </td>

        </tr>
        <?php endforeach; ?>
    </table>
    </div>

      

</body>
</html>