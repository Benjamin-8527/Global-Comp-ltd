<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "computer_store");
if ($_SERVER["REQUEST_METHOD"] == "POST") {

$order_id = $_POST['order_id'];
$action = $_POST['action'];

$status = ($action === "Approve") ? "Approved" : "Rejected";

$conn->query("UPDATE orders SET status='$status' WHERE id=$order_id");

header("Location: admin_dashboard.php");
exit();
}
?>
