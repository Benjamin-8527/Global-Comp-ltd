<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
</head>
<body>
<h2>My Order History</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>Product Name</th>
        <th>Order Type</th>
        <th>Order Date</th>
        <th>Status</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['order_type']) ?></td>
            <td><?= $row['order_date'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<br>
<a href="customer_dashboard.php">Back to Dashboard</a>
</body>
</html>
