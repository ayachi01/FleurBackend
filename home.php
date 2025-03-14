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
        <a href="account.html"><i class="fa-solid fa-user"></i> Account</a>
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
        </div>

        <div class="card">
            <h2>Statistics</h2>
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = 'logout.php'; // Redirect to logout page
            }
        }

        // Ensure the script runs after the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            const salesData = [65, 59, 80, 81, 56, 55, 40]; // Sales data
            const totalSales = salesData.reduce((acc, curr) => acc + curr, 0); // Calculate total sales

            // Display total sales in the designated HTML element
            document.getElementById('totalSales').innerText = `Total Sales: $${totalSales}`;

            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'bar', // Type of chart (bar, line, pie, etc.)
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], // X-axis labels
                    datasets: [{
                        label: 'Sales',
                        data: salesData, // Use the sales data array
                        backgroundColor: 'rgba(256, 212, 220, 0.2)', // Bar color
                        borderColor: 'rgba(256, 212, 220, 1)', // Border color
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true // Start Y-axis at zero
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>