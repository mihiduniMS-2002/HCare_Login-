<?php
require_once "config/db.php";

$msg   = "";
$type  = "";
$show  = false;   // form eka pennanna one da
$done  = false;

$token = $_GET["token"] ?? $_POST["token"] ?? "";
$hash  = hash("sha256", $token);

// token eka hari da, expire wela da balanna
$stmt = $conn->prepare(
    "SELECT user_id FROM password_resets
     WHERE token_hash = ? AND expires_at > NOW()"
);
$stmt->bind_param("s", $hash);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($row) {
    $show = true;
} else {
    $msg  = "This reset link is invalid or expired.";
    $type = "error";
}

if ($show && isset($_POST["reset"])) {

    $pass    = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if ($pass !== $confirm) {
        $msg  = "Passwords do not match";
        $type = "error";

    } elseif (strlen($pass) < 8) {
        $msg  = "Password must be at least 8 characters";
        $type = "error";

    } else {
        $id  = $row["user_id"];
        $new = password_hash($pass, PASSWORD_DEFAULT);

        // password eka update karanna
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $new, $id);
        $stmt->execute();

        // token eka ayin karanna (ekakata witharai use karanna puluwan)
        $conn->query("DELETE FROM password_resets WHERE user_id = $id");

        $show = false;
        $done = true;
        $msg  = "Password changed! You can login now.";
        $type = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - H-Care</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">

        <h2 class="logo">✚ H-care</h2>
        <div class="auth-icon">🔒</div>
        <h2>Reset Password</h2>
        <p class="auth-description">Enter your new password.</p>

        <?php if ($msg) { ?>
            <p class="<?= $type ?>"><?= htmlspecialchars($msg) ?></p>
        <?php } ?>

        <?php if ($show) { ?>
            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <label>New Password</label>
                <input type="password" name="password" placeholder="New password" minlength="8" required>

                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm password" minlength="8" required>

                <button type="submit" name="reset">Reset Password</button>
            </form>
        <?php } ?>

        <p class="back-link">
            <?php if ($done) { ?>
                <a href="login.php">Go to Login</a>
            <?php } elseif (!$show) { ?>
                <a href="forgot-password.php">Get a new link</a>
            <?php } else { ?>
                <a href="login.php">Back to Login</a>
            <?php } ?>
        </p>

    </div>
</div>

</body>
</html>