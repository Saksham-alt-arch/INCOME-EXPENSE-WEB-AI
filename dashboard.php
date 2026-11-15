<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$username = htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8");

// Calculate income and expenses
$stmt = $conn->prepare(
    "SELECT
        COALESCE(SUM(CASE WHEN type = 'Income' THEN amount ELSE 0 END), 0) AS total_income,
        COALESCE(SUM(CASE WHEN type = 'Expense' THEN amount ELSE 0 END), 0) AS total_expenses
     FROM transactions
     WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$totals = $stmt->get_result()->fetch_assoc();

$stmt->close();

$total_income = $totals["total_income"];
$total_expenses = $totals["total_expenses"];
$balance = $total_income - $total_expenses;

// Get user's transactions
$stmt = $conn->prepare(
    "SELECT * FROM transactions
     WHERE user_id = ?
     ORDER BY transaction_date DESC, transaction_id DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$transactions = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Personal Finance Tracker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Personal Finance Tracker</h1>

    <h2>Welcome, <?= $username ?>!</h2>

    <h3>Financial Summary</h3>

    <p>Total Income: $<?= number_format($total_income, 2) ?></p>

    <p>Total Expenses: $<?= number_format($total_expenses, 2) ?></p>

    <p>Current Balance: $<?= number_format($balance, 2) ?></p>

    <hr>

    <h2>Transactions</h2>

    <a href="transaction.php">Add New Transaction</a>

    <br><br>

    <?php if ($transactions->num_rows > 0): ?>

        <table border="1" cellpadding="8">
            <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $transactions->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["type"]) ?></td>
                    <td>$<?= number_format($row["amount"], 2) ?></td>
                    <td><?= htmlspecialchars($row["description"]) ?></td>
                    <td><?= htmlspecialchars($row["transaction_date"]) ?></td>
                    <td>
                        <a href="edit_transaction.php?id=<?= $row["transaction_id"] ?>">
                            Edit
                        </a>

                        <form method="POST" action="delete_transaction.php">
                            <input type="hidden" name="transaction_id"
                                value="<?= $row["transaction_id"] ?>">

                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>

    <?php else: ?>

        <p>No transactions yet.</p>

    <?php endif; ?>

    <br>

    <a href="logout.php">Log Out</a>

</body>
</html>

<?php
$stmt->close();
?>
