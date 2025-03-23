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
    <title>Login</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>


</head>

<body>
    <div class="page">
        <div class="container">
            <div class="signup-content">
                <img src="assets/fleur haven.png" alt="Login Image" class="signup-image">
                <form method="POST" action="../FleurBackend/api/index_api.php" class="formLogin">
                    <h1>Welcome</h1>
                    
                    <?php if ($error): ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?> 

                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="email@example.com" name="email" required>

                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="Password123" required>
                        <label for="show_password" class="toggle-password">
                            <i class="fas <?php echo $showPassword ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                        </label>
                    </div>
                    <button type="submit" class="submit-btn">Sign In</button>   
                    <a href="forgotpass.php" class="forgotpass">Forgot Password?</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
