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
</head>

<body>
    <div class="page">
        <div class="container">
            <div class="signup-content">
                <img src="assets/fleur haven.png" alt="Logo" class="signup-image">
                <form method="POST" action="../FleurBackend/api/send_reset_email.php" class="formLogin">
                    <h1>Forgot Password</h1>

                    <?php if ($error): ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <label for="email">Enter Your Email</label>
                    <input type="email" id="email" name="email" required>

                    <button type="submit" class="submit-btn">Send Reset Link</button>   
                    <a href="index.php">Sign in</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
