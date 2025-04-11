<?php
session_start();
require_once 'includes/auth_secure.php';
require_once 'includes/popup.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation logic
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered";
        } else {
            // Hash password and insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hashed_password);

            if ($insert->execute()) {
                $_SESSION['user'] = $username;
                showPopup("Registration Successful!");
                header("Refresh: 2; URL=login.php");
                exit();
            } else {
                $error = "Registration failed: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sign Up | Premium Cinema</title>
    <link rel="stylesheet" href="style/styles.css">

    <link rel="stylesheet" href="style/premium_auth.css">

</head>

<body>
    <div class="auth-container">
        <h1>Join Premium Cinema</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required value="">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="">
            </div>
            <div class="form-group">
                <label>Password (min 8 characters)</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn">Create Account</button>
        </form>
        <div class="auth-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>

</html>