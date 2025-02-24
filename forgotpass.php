<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Clear error message after displaying it
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="page">
        <div class="container">
            <div class="signup-content">
                <img src="assets/fleur haven.png" alt="Login Image" class="signup-image">
                <form method="POST" action="../FleurHaven/api/login_api.php" class="formLogin">
                    <h1> Change Password </h1>
                    <?php if ($error): ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <label for="email">Old Password</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">New Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="submit-btn">Update</button>   
                    <a href="index.php">Sign in </a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
