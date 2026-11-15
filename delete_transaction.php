<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $transaction_id = filter_input(
        INPUT_POST,
        "transaction_id",
        FILTER_VALIDATE_INT
    );

    if ($transaction_id) {
        $user_id = $_SESSION["user_id"];

        $stmt = $conn->prepare(
            "DELETE FROM transactions
             WHERE transaction_id = ? AND user_id = ?"
        );

        $stmt->bind_param("ii", $transaction_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: dashboard.php");
exit;
?>
