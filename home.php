<?php
session_start(); // Start the session

// Database connection (make sure to include your connection code)
require 'api/db.php'; // Adjust the path as necessary

// Query to count total users
$sqlUsers = "SELECT COUNT(*) as total FROM users";
$resultUsers = $connection->query($sqlUsers);

if ($resultUsers->num_rows > 0) {
    $row = $resultUsers->fetch_assoc();
    $totalUsers = $row['total'];
} else {
    $totalUsers = 0;
}

// Query to get total quantities sold for each flower
$sqlFlowers = "SELECT 
    flowers.name, 
    COALESCE(SUM(order_items.quantity), 0) AS total_quantity
FROM 
    flowers
LEFT JOIN 
    order_items ON order_items.flower_id = flowers.id
GROUP BY 
    flowers.name;";
$resultFlowers = $connection->query($sqlFlowers);

$flowerNames = [];
$salesData = [];
$totalSales = 0;

if ($resultFlowers->num_rows > 0) {
    while ($row = $resultFlowers->fetch_assoc()) {
        $flowerNames[] = $row['name'];
        $salesData[] = $row['total_quantity'];
        $totalSales += $row['total_quantity'] ; 
    }
} else {
    $flowerNames = ['No Data'];
    $salesData = [0];
}

// Query to get total orders (replace this with the correct query)
$sqlOrders = "SELECT COUNT(*) as total_orders FROM orders";
$resultOrders = $connection->query($sqlOrders);
if ($resultOrders->num_rows > 0) {
    $row = $resultOrders->fetch_assoc();
    $totalOrders = $row['total_orders'];
} else {
    $totalOrders = 0;
}

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    // Echo user information
    $welcomeMessage = "Welcome, " . htmlspecialchars($_SESSION['email']) . "!";
    $userEmail = htmlspecialchars($_SESSION['email']);
} else {
    $welcomeMessage = "You are not logged in.";
    $userEmail = "";
}
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
    
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>
<body>
    <div class="sidebar">
        <img src="assets/logo.png" alt="" class="signup-image">
        <a href="home.php"><i class="fa-solid fa-house"></i> Home </a>
        <a href="orders.php"><i class="fa-solid fa-truck"></i> Orders</a>
        <a href="inventory.php"><i class="fa-solid fa-warehouse"></i> Inventory</a>
        <a href="customers.php"><i class="fa-solid fa-users"></i> Customers</a>
        <button class="logout-button" onclick="confirmLogout()"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
    </div>

    <div class="main-content">
        <div class="card2">
            <h2>Dashboard</h2>
            <p><?php echo $welcomeMessage; ?></p> <!-- Display welcome message -->
        </div>
        
        <!-- Total Sales and Total Users Boxes -->
        <div class="total-boxes">
            <div class="total-sales" id="totalSales">
                Total Sales: $<?php echo number_format($totalSales, 2); ?>
            </div>
            <div class="total-users" id="totalUsers">
                Total Customers: <?php echo $totalUsers; ?>
            </div>
            <div class="total-orders" id="totalOrders">
                Total Orders: <?php echo $totalOrders; ?>
            </div>
        </div>

        <div class="statistics-container">
            <div class="chart-container">
                <div class="card">
                    <h2>Sales Chart</h2>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <div class="card">
                    <h2>Overall Statistics</h2>
                    <div class="pie-chart-container">
                        <canvas id="pieChart" width="250" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const salesData = <?php echo json_encode($salesData); ?>; // Sales data from PHP
            const flowerNames = <?php echo json_encode($flowerNames); ?>; // Flower names from PHP
            const totalSales = <?php echo $totalSales; ?>; // Total sales from PHP

            // Display total sales in the designated HTML element
            document.getElementById('totalSales').innerText = `Total Sales: $${totalSales.toFixed(2)}`;

            const ctxBar = document.getElementById('myChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar', 
                data: {
                    labels: flowerNames, // Use flower names as labels
                    datasets: [{
                        label: 'Quantity Sold',
                        data: salesData, // Use sales data
                        backgroundColor: 'rgba(256, 212, 220, 0.2)',
                        borderColor: 'rgba(256, 212, 220, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            const totalUsers = <?php echo $totalUsers; ?>;
            const totalOrders = <?php echo $totalOrders; ?>; // Placeholder, update if necessary
            
            const ctxPie = document.getElementById('pieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Total Sales', 'Total Users', 'Total Orders'],
                    datasets: [{
                        data: [totalSales, totalUsers, totalOrders],
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'left', // Position the legend on the left
                        }
                    }
                }
            });    
        });
        </script>
    </div>
</body>
</html>
