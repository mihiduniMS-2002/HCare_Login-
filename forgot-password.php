<?php
require_once "config/db.php";

$devMode = true; // real server ekata giyama false karanna
$msg = "";
$link = "";

if (isset($_POST["send"])) {

    $email = trim($_POST["email"]);

    // email eka thiyenawada balanna
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {

        $id    = $row["user_id"];
        $token = bin2hex(random_bytes(32));
        $hash  = hash("sha256", $token);

        // palaweni token eka ayin karanna
        $conn->query("DELETE FROM password_resets WHERE user_id = $id");

        // aluth token eka save karanna (1 hour valid)
        $stmt = $conn->prepare(
            "INSERT INTO password_resets (user_id, token_hash, expires_at)
             VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))"
        );
        $stmt->bind_param("is", $id, $hash);
        $stmt->execute();

        $link = "http://localhost/HCare/reset-password.php?token=" . $token;
    }

    // email eka thiyenawada kiyala pennanne na
    $msg = "If the email is registered, a reset link has been created.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - H-Care</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">

        <h2 class="logo">✚ H-care</h2>
        <div class="auth-icon">🔑</div>
        <h2>Forgot Password?</h2>
        <p class="auth-description">Enter your email to get a reset link.</p>

        <?php if ($msg) { ?>
            <p class="success"><?= htmlspecialchars($msg) ?></p>
        <?php } ?>

        <?php if ($devMode && $link) { ?>
            <p class="success">
                <b>DEV MODE</b> - reset link (1 hour):<br>
                <a href="<?= htmlspecialchars($link) ?>"><?= htmlspecialchars($link) ?></a>
            </p>
        <?php } ?>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required maxlength="100">

            <button type="submit" name="send">Send Reset Link</button>
        </form>

        <p class="back-link">
            Remembered it? <a href="login.php">Login</a>
        </p>

    </div>
</div>

</body>
</html>