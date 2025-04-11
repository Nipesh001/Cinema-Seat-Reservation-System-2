<?php
session_start();
require_once 'includes/auth_secure.php';
require_once 'includes/popup.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Both email and password are required";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user'] = $user['username'];
                
                // Set remember me cookie if requested
                if (isset($_POST['remember']) && $_POST['remember'] == '1') {
                    $token = bin2hex(random_bytes(32));
                    $token_hash = password_hash($token, PASSWORD_DEFAULT);
                    $expiry = time() + 60 * 60 * 24 * 30; // 30 days
                    
                    $stmt = $conn->prepare("INSERT INTO auth_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $user['id'], $token_hash, $expiry);
                    $stmt->execute();
                    
                    setcookie('remember_token', $token, [
                        'expires' => $expiry,
                        'path' => '/',
                        'domain' => '',
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]);
                }
                
                showPopup("Login Successful!");
                header("Refresh: 2; URL=index.php");
                exit();
            }
        }
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login | Premium Cinema</title>
    <link rel="stylesheet" href="style/premium_auth.css">
    <link rel="stylesheet" href="style/styles.css">
</head>

<body>
    <div class="auth-container">
        <h1>Welcome Back</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember" value="1"> Remember me
                </label>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="auth-links">
            <p>New to Premium Cinema? <a href="signup.php">Create account</a></p>
        </div>
    </div>
</body>

</html>