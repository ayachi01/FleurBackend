<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
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

<?php
$error = '';

function containsSpecialCharacter($password) {
    return preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (!containsSpecialCharacter($password)) {
        $error = 'Password must contain at least one special character.';
    } else {
        // Proceed with password reset logic (e.g., hash the password and update the database)
        // Example: $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Update the database with the new password using the token
        // ...
        
        // Redirect or show success message
        // header('Location: success.php');
        // exit();
    }
}
?>

<body>
    <div class="page">
        <div class="container">
            <div class="signup-content">
                <img src="assets/fleur haven.png" alt="Logo" class="signup-image">
                <form method="POST" action="" class="formLogin">
                    <h1>Reset Password</h1>

                    <?php if ($error): ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <!-- Hidden Token (Comes from URL) -->
                    <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">

                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                    </div>
                    <label class="show-password">
                        <input type="checkbox" onclick="this.previousElementSibling.type = this.checked ? 'text' : 'password'">
                        Show Password
                    </label>

                    <label for="confirm_password">Confirm Password</label>
                    <div class="password-container">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <label class="show-password">
                        <input type="checkbox" onclick="this.previousElementSibling.type = this.checked ? 'text' : 'password'">
                        Show Password
                    </label>

                    <button type="submit" class="submit-btn">Update</button>   
                    <a href="index.php">Sign in</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
