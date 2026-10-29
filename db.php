
<?php
$host = "LocalHost";
$username = "root";
$password = "MAHSKAS@Hell99";
$database = "personal_finance_tracker";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
