<?php
session_start(); // Start the session

// Database connection (make sure to include your connection code)
require 'api/db.php'; // Adjust the path as necessary

// Query to count total users
$sql = "SELECT COUNT(*) as total FROM users";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalUsers = $row['total'];
} else {
    $totalUsers = 0;
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
                Total Sales: $0
            </div>
            <div class="total-users" id="totalUsers">
                Total Customers: <?php echo $totalUsers; ?>
            </div>
            <div class="total-orders" id="totalOrders">
                Total Orders: <?php echo $totalUsers; ?>
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
            const salesData = [65, 59, 80, 81, 56, 55, 40]; // Sales data
            const totalSales = salesData.reduce((acc, curr) => acc + curr, 0); // Calculate total sales

            // Display total sales in the designated HTML element
            document.getElementById('totalSales').innerText = `Total Sales: $${totalSales}`;

            const ctxBar = document.getElementById('myChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar', 
                data: {
                    labels: ['Assorted Flowers', 'Orchids', 'Carnation', 'Daisy Kiss', 'Dangwa', 'Eternal', 'Everyday', 'Just Because', 'Lilies', 'Pixie Posy', 'True Love', 'Valentine', 'White Daisies', 'White Roses', 'White Tulips'], 
                    datasets: [{
                        label: 'Sales',
                        data: salesData,
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
            const totalOrders = <?php echo $totalUsers; ?>; // Placeholder, update if necessary
            
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