
<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$message = "";

$transaction_id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $transaction_id = filter_input(
        INPUT_POST,
        "transaction_id",
        FILTER_VALIDATE_INT
    );

    $type = $_POST["type"] ?? "";
    $amount = $_POST["amount"] ?? "";
    $description = trim($_POST["description"] ?? "");
    $date = $_POST["transaction_date"] ?? "";

    if (
        !$transaction_id ||
        !in_array($type, ["Income", "Expense"], true) ||
        !is_numeric($amount) ||
        !is_finite((float)$amount) ||
        (float)$amount <= 0 ||
        (float)$amount > 99999999.99 ||
        $description === "" ||
        strlen($description) > 255 ||
        !DateTime::createFromFormat("!Y-m-d", $date) ||
        DateTime::createFromFormat("!Y-m-d", $date)->format("Y-m-d") !== $date
    ) {
        $message = "Please enter valid information.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE transactions
             SET type = ?, amount = ?, description = ?, transaction_date = ?
             WHERE transaction_id = ? AND user_id = ?"
        );

        $stmt->bind_param(
            "sdssii",
            $type,
            $amount,
            $description,
            $date,
            $transaction_id,
            $user_id
        );

        $stmt->execute();

        if ($stmt->affected_rows >= 0) {
            $stmt->close();
            header("Location: dashboard.php");
            exit;
        }

        $message = "Unable to update transaction.";
        $stmt->close();
    }
}

if (!$transaction_id) {
    die("Invalid transaction.");
}

$stmt = $conn->prepare(
    "SELECT * FROM transactions
     WHERE transaction_id = ? AND user_id = ?"
);

$stmt->bind_param("ii", $transaction_id, $user_id);
$stmt->execute();

$transaction = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$transaction) {
    die("Transaction not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Edit Transaction</h1>

    <p><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>

    <form method="POST" action="edit_transaction.php">
        <input type="hidden" name="transaction_id"
               value="<?= $transaction["transaction_id"] ?>">

        <label>Type:</label>
        <select name="type" required>
            <option value="Income"
                <?= $transaction["type"] === "Income" ? "selected" : "" ?>>
                Income
            </option>

            <option value="Expense"
                <?= $transaction["type"] === "Expense" ? "selected" : "" ?>>
                Expense
            </option>
        </select>

        <br><br>

        <label>Amount:</label>
        <input type="number" name="amount" min="0.01"
               max="99999999.99" step="0.01"
               value="<?= htmlspecialchars((string)$transaction["amount"]) ?>"
               required>

        <br><br>

        <label>Description:</label>
        <input type="text" name="description" maxlength="255"
               value="<?= htmlspecialchars($transaction["description"]) ?>"
               required>

        <br><br>

        <label>Date:</label>
        <input type="date" name="transaction_date"
               value="<?= htmlspecialchars($transaction["transaction_date"]) ?>"
               required>

        <br><br>

        <button type="submit">Update Transaction</button>
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>

</body>
</html>
