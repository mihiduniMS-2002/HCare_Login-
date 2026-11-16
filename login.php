<?php
session_start();
require_once "config/db.php";

// login wela inna kenek nam dashboard ekata yanna
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (isset($_POST["login"])) {

    $email = trim($_POST["email"]);
    $pass  = $_POST["password"];

    $sql = "SELECT user_id, full_name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row && password_verify($pass, $row["password"])) {
        $_SESSION["user_id"]   = $row["user_id"];
        $_SESSION["full_name"] = $row["full_name"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Wrong email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - H-Care</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">

        <h2 class="logo">✚ H-care</h2>
        <div class="auth-icon">🔐</div>
        <h2>Login</h2>
        <p class="auth-description">Login to your H-Care account.</p>

        <?php if ($error) { ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <button type="submit" name="login">Login</button>

            <p class="forgot-link">
                <a href="forgot-password.php">Forgot password?</a>
            </p>
        </form>

        <p class="back-link">
            No account yet? <a href="register.php">Register</a>
        </p>

    </div>
</div>

</body>
</html>