
<?php
require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($password) < 8) {
        $message = "Enter a username, valid email, and password of at least 8 characters.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $username, $email, $password_hash);

        if ($stmt->execute()) {
            $message = "Account created successfully!";
        } else {
            $message = $stmt->errno === 1062
                ? "That email is already registered."
                : "Could not create account. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Personal Finance Tracker</title>
</head>
<body>
    <h1>Create an Account</h1>

    <p><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>

    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" maxlength="50" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="100" required>
        <br><br>

        <label for="password">Password (at least 8 characters):</label>
        <input type="password" id="password" name="password" minlength="8" required>
        <br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Log in</a></p>
    <a href="index.php">Back to Home</a>
</body>
</html>
