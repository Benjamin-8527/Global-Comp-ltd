<?php
require('fpdf.php');
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

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Global Computer Ltd - Sales Report', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 8, 'ID', 1);
$pdf->Cell(40, 8, 'Customer', 1);
$pdf->Cell(20, 8, 'Gender', 1);
$pdf->Cell(35, 8, 'Product', 1);
$pdf->Cell(15, 8, 'Qty', 1);
$pdf->Cell(30, 8, 'Total', 1);
$pdf->Cell(35, 8, 'Date', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(15, 8, $row['id'], 1);
    $pdf->Cell(40, 8, $row['customer_name'], 1);
    $pdf->Cell(20, 8, ucfirst($row['gender']), 1);
    $pdf->Cell(35, 8, $row['product_name'], 1);
    $pdf->Cell(15, 8, $row['quantity'], 1);
    $pdf->Cell(30, 8, number_format($row['total_amount'], 2), 1);
    $pdf->Cell(35, 8, $row['created_at'], 1);
    $pdf->Ln();
}

$pdf->Output("I", "sales_report.pdf");
exit;
