
<?php

include "config/db.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {

        $message = "Passwords do not match!";
        $messageType = "error";

    } elseif (strlen($password) < 8) {

        $message = "Password must contain at least 8 characters!";
        $messageType = "error";

    } else {

        $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "Email already registered!";
            $messageType = "error";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)"
            );

            $stmt->bind_param("sss", $full_name, $email, $hashed_password);

            if ($stmt->execute()) {

                $message = "Registration successful! You can now login.";
                $messageType = "success";

            } else {

                $message = "Registration failed. Please try again.";
                $messageType = "error";

            }

            $stmt->close();
        }

        $check->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - H-Care</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="auth-container">

        <div class="auth-box">

            <h2 class="logo">✚ H-care</h2>

            <div class="auth-icon">👤</div>

            <h2>Create Account</h2>

            <p class="auth-description">
                Register to access the H-Care system.
            </p>

            <?php if ($message != "") { ?>

                <p class="<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </p>

            <?php } ?>

            <form method="POST" action="">

                <label>Full Name</label>

                <input
                    type="text"
                    name="full_name"
                    placeholder="Enter your full name"
                    required
                    maxlength="100"
                    value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
                >

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                    maxlength="100"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                >

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter password"
                    minlength="8"
                    required
                >

                <label>Confirm Password</label>

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Confirm password"
                    minlength="8"
                    required
                >

                <button type="submit" name="register">
                    Register
                </button>

            </form>

            <p class="back-link">
                Already have an account?
                <a href="login.php">Login</a>
            </p>

        </div>

    </div>

</body>
</html>
