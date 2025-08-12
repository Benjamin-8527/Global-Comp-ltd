<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    die("Unauthorized");
}

$conn = new mysqli("localhost", "root", "", "computer_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$period = $_GET['period'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$gender = $_GET['gender'] ?? '';

$where = "WHERE o.status = 'approved'";
$params = [];
$types = "";

// Period
if ($period == 'today') {
    $where .= " AND DATE(o.created_at) = CURDATE()";
} elseif ($period == 'week') {
    $where .= " AND YEARWEEK(o.created_at, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($period == 'month') {
    $where .= " AND MONTH(o.created_at) = MONTH(CURDATE()) AND YEAR(o.created_at) = YEAR(CURDATE())";
}

// Date range
if (!empty($start_date) && !empty($end_date)) {
    $where .= " AND DATE(o.created_at) BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
    $types .= "ss";
}

// Gender
if (!empty($gender)) {
    $where .= " AND c.gender = ?";
    $params[] = $gender;
    $types .= "s";
}

$sql = "SELECT o.id, c.name as customer_name, c.gender, p.name as product_name, o.quantity, o.total_amount, o.created_at 
        FROM orders o
        JOIN products p ON o.product_id = p.id
        JOIN customers c ON o.customer_id = c.id
        $where
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="sales_report.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Order ID', 'Customer', 'Gender', 'Product', 'Quantity', 'Total (KES)', 'Date']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['id'], $row['customer_name'], $row['gender'], $row['product_name'], $row['quantity'], $row['total_amount'], $row['created_at']]);
}
fclose($output);
exit;
