<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "computer_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Approve
if (isset($_GET['approve_id'])) {
    $order_id = intval($_GET['approve_id']);
    $stmt = $conn->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
}

// Filters
$status_filter = $_GET['status'] ?? '';
$date_filter = $_GET['date'] ?? '';

// Build query
$sql = "SELECT o.*, c.name AS customer_name, p.name AS product_name FROM orders o 
        JOIN customers c ON o.customer_id = c.id 
        JOIN products p ON o.product_id = p.id
        WHERE 1";

$params = [];
$types = "";

// Add status filter
if ($status_filter) {
    $sql .= " AND o.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

// Add date range filter
if ($date_filter) {
    $sql .= " AND o.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
    $params[] = intval($date_filter);
    $types .= "i";
}

$sql .= " ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 230px;
            background-color: #1f2937;
            color: white;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
        }
        .sidebar h2 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #2563eb;
        }
        .content {
            margin-left: 230px;
            padding: 20px;
            width: calc(100% - 230px);
        }
        h2 {
            color: #007bff;
        }
        .filter-form {
            margin-bottom: 20px;
        }
        .filter-form select {
            padding: 8px;
            margin: 0 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0 0 12px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .approve-link {
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .approve-link:hover {
            background-color: #218838;
        }
        footer {
            background-color: #1f2937;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin dashboard.php">🏠 Dashboard</a> <!-- No underscore -->
    <a href="manage_products.php">🖥️ Products</a>
    <a href="admin_add_product.php">➕ Add Product</a>
    <a href="admin_orders.php" class="active">📦 Orders</a>
    <a href="admin_view_requests.php">📢 Marketing</a>
    <a href="admin_view_contacts.php">📩 Contacts</a>
    <a href="sales.php">💰 Sales</a>
    <a href="admin_logout.php" style="color: red;">🚪 Logout</a>
</div>


<div class="content">
    <h2>Manage Orders</h2>

    <form class="filter-form" method="GET">
        <label>Status:</label>
        <select name="status" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="approved" <?= $status_filter == 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="declined" <?= $status_filter == 'declined' ? 'selected' : '' ?>>Declined</option>
        </select>

        <label>Date Range:</label>
        <select name="date" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="7" <?= $date_filter == '7' ? 'selected' : '' ?>>Last 7 days</option>
            <option value="30" <?= $date_filter == '30' ? 'selected' : '' ?>>Last 30 days</option>
            <option value="90" <?= $date_filter == '90' ? 'selected' : '' ?>>Last 90 days</option>
        </select>
    </form>

    <table>
        <tr>
            <th>Customer</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Amount (KES)</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= number_format($row['total_amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <?php if ($row['status'] !== 'approved'): ?>
                            <a class="approve-link" href="?approve_id=<?= $row['id'] ?>">Approve</a>
                        <?php else: ?>
                            ✔️ Approved
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No orders found.</td></tr>
        <?php endif; ?>
    </table>

    <footer>
        &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
    </footer>
</div>

</body>
</html>
