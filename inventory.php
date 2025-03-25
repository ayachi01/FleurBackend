<?php
require 'api/db.php'; // Include database connection

// Handle delete request
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete_sql = "DELETE FROM flowers WHERE id = ?";
    $stmt = $connection->prepare($delete_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: inventory.php"); // Redirect to the inventory page
    exit();
}

// Handle edit request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $update_sql = "UPDATE flowers SET name = ?, price = ?, stock = ? WHERE id = ?";
    $stmt = $connection->prepare($update_sql);
    $stmt->bind_param("sdii", $name, $price, $stock, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: inventory.php"); // Redirect to the inventory page
    exit();
}

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
    <a href="orders.php"><i class="fa-solid fa-truck"></i> Orders</a>
    <a href="inventory.php"><i class="fa-solid fa-warehouse"></i> Inventory</a>
    <a href="customers.php"><i class="fa-solid fa-users"></i> Customers</a>
    <a href="account.html"><i class="fa-solid fa-user"></i> Account</a>
    <button class="logout-button" onclick="confirmLogout()"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
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
                    <th>Action</th>
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
                        echo $flower['stock'] > 0 ? 'Available' : 'Out of Stock'; 
                        ?>
                    </td>
                    <td>
                        <button class="edit" onclick="openEditModal(<?php echo $flower['id']; ?>, '<?php echo htmlspecialchars($flower['name']); ?>', <?php echo $flower['price']; ?>, <?php echo $flower['stock']; ?>)">Edit</button>
                        <a href="?action=delete&id=<?php echo $flower['id']; ?>" class="delete"onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Flower</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="editId">
            <label for="name">Name:</label>
            <input type="text" name="name" id="editName" required>
            <label for="price">Price:</label>
            <input type="number" name="price" id="editPrice" step="0.01" required>
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="editStock" required>
            <button type="submit" name="edit">Update</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, price, stock) {
    document.getElementById('editId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editPrice').value = price;
    document.getElementById('editStock').value = stock;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = 'logout.php'; // Redirect to logout page
            }
        }

</script>

</body>
</html>