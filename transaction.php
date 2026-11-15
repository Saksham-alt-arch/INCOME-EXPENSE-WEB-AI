<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = $_POST["type"] ?? "";
    $amount = $_POST["amount"] ?? "";
    $description = trim($_POST["description"] ?? "");
    $date = $_POST["transaction_date"] ?? "";

    if (
        !in_array($type, ["Income", "Expense"], true) ||
        !is_numeric($amount) ||
        $amount <= 0 ||
        $description === "" ||
        $date === ""
    ) {
        $message = "Please enter valid transaction information.";
    } else {
        $user_id = $_SESSION["user_id"];

        $stmt = $conn->prepare(
            "INSERT INTO transactions
            (user_id, type, amount, description, transaction_date)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "isdss",
            $user_id,
            $type,
            $amount,
            $description,
            $date
        );

        if ($stmt->execute()) {
            $message = "Transaction added successfully!";
        } else {
            $message = "Unable to save transaction.";
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
    <title>Add Transaction</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Add Transaction</h1>

    <p><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>

    <form method="POST" action="transaction.php" id="transactionForm">

        <label for="type">Transaction Type:</label>
        <select name="type" id="type" required>
            <option value="Income">Income</option>
            <option value="Expense">Expense</option>
        </select>

        <br><br>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount"
               min="0.01" step="0.01" required>

        <br><br>

        <label for="description">Description:</label>
        <input type="text" name="description" id="description"
            maxlength="255">

        <br><br>

        <label for="transaction_date">Date:</label>
        <input type="date" name="transaction_date"
            id="transaction_date">

        <br><br>

        <button type="submit">Save Transaction</button>

    </form>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>
    <script src="js/validation.js"></script>

</body>
</html>
