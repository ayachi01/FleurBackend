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
    <title>Reset Password</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="page">
        <div class="container">
            <div class="signup-content">
                <img src="assets/fleur haven.png" alt="Logo" class="signup-image">
                <form method="POST" action="../FleurBackend/api/reset_pass.php" class="formLogin">
                    <h1>Reset Password</h1>

                    <?php if ($error): ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <!-- Hidden Token (Comes from URL) -->
                    <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">

                    <label for="new_password">New Password</label>
                    <div class="password-container">
                        <input type="password" id="new_password" name="new_password" required>
                    </div>

                    <button type="submit" class="submit-btn">Update</button>   
                    <a href="index.php">Sign in</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
