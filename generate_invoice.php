<?php
session_start();
if (!isset($_SESSION['customer_logged_in'])) {
    header("Location: login.php");
    exit();
}

require('fpdf.php');
$conn = new mysqli("localhost", "root", "", "computer_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = intval($_GET['order_id']);
$customer_id = $_SESSION['customer_id'];
$theme = $_GET['theme'] ?? 'light';

$stmt = $conn->prepare("SELECT o.*, p.name AS product_name, p.price, c.name AS customer_name, c.email
                        FROM orders o 
                        JOIN products p ON o.product_id = p.id 
                        JOIN customers c ON o.customer_id = c.id
                        WHERE o.id = ? AND o.customer_id = ?");
$stmt->bind_param("ii", $order_id, $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    die("⚠️ Order not found or you are not authorized to access it.");
}

// --- PDF class ---
class ThemedPDF extends FPDF {
    public $theme;
    public $logo_path = 'logo.png'; // Change this if your logo file is elsewhere

    function Header() {
        // Logo
        if (file_exists($this->logo_path)) {
            $this->Image($this->logo_path, 10, 6, 30);
            $this->Ln(20);
        }
        if ($this->theme == 'dark') {
            $this->SetFillColor(40, 40, 40);
            $this->Rect(0, 0, $this->GetPageWidth(), 40, 'F');
            $this->SetTextColor(255, 255, 255);
        } else {
            $this->SetTextColor(0, 0, 0);
        }
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Global Computer Ltd', 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, 'Order Invoice', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 10);
        if ($this->theme == 'dark') {
            $this->SetTextColor(255, 255, 255);
        } else {
            $this->SetTextColor(0, 0, 0);
        }
        $this->Cell(0, 10, 'Thank you for your purchase! - Global Computer Ltd', 0, 0, 'C');
    }
}

$pdf = new ThemedPDF();
$pdf->theme = $theme;
$pdf->AddPage();

if ($theme == 'dark') {
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFillColor(40, 40, 40);
    $pdf->Rect(0, 40, $pdf->GetPageWidth(), $pdf->GetPageHeight() - 60, 'F');
} else {
    $pdf->SetTextColor(0, 0, 0);
}

// --- Customer info ---
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Customer Name: ' . $order['customer_name'], 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $order['email'], 0, 1);
$pdf->Ln(5);

// --- Order info ---
$pdf->Cell(0, 10, 'Order ID: ' . $order['id'], 0, 1);
$pdf->Cell(0, 10, 'Order Date: ' . $order['created_at'], 0, 1);
$pdf->Ln(5);

// --- Table header ---
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(0, 123, 255); // Bootstrap blue
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(80, 10, 'Product', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Price', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Total', 1, 1, 'C', true);

// --- Table row ---
$pdf->SetFont('Arial', '', 12);
if ($theme == 'dark') {
    $pdf->SetTextColor(255, 255, 255);
} else {
    $pdf->SetTextColor(0, 0, 0);
}
$pdf->Cell(80, 10, $order['product_name'], 1);
$pdf->Cell(30, 10, number_format($order['price'], 2), 1);
$pdf->Cell(30, 10, $order['quantity'], 1);
$pdf->Cell(40, 10, number_format($order['total_amount'], 2), 1);
$pdf->Ln(20);

// --- Save file on server ---
$save_path = "invoices/Invoice_Order_" . $order['id'] . ".pdf";
if (!is_dir("invoices")) {
    mkdir("invoices", 0777, true);
}
$pdf->Output("F", $save_path); // Save file

// --- Show in browser too ---
$pdf->Output("I", "Invoice_Order_" . $order['id'] . ".pdf");
?>
