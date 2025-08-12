<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn = new mysqli("localhost", "root", "", "computer_store");
    $conn->query("DELETE FROM customer WHERE id = $id");
}

header("Location: manage_customers.php");
exit();
