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

$period = $_GET['period'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$gender = $_GET['gender'] ?? '';

$where = "WHERE o.status = 'approved'";
$params = [];
$types = "";

if ($period == 'today') {
    $where .= " AND DATE(o.created_at) = CURDATE()";
} elseif ($period == 'week') {
    $where .= " AND YEARWEEK(o.created_at, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($period == 'month') {
    $where .= " AND MONTH(o.created_at) = MONTH(CURDATE()) AND YEAR(o.created_at) = YEAR(CURDATE())";
}

if (!empty($start_date) && !empty($end_date)) {
    $where .= " AND DATE(o.created_at) BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
    $types .= "ss";
}

if (!empty($gender)) {
    $where .= " AND c.gender = ?";
    $params[] = $gender;
    $types .= "s";
}

$sql_summary = "SELECT 
                    SUM(o.total_amount) AS total_sales,
                    COUNT(o.id) AS total_orders,
                    SUM(o.total_amount * 0.15) AS profit
                FROM orders o
                JOIN products p ON o.product_id = p.id
                JOIN customers c ON o.customer_id = c.id
                $where";

$stmt = $conn->prepare($sql_summary);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

$total_sales = $result['total_sales'] ?? 0;
$total_orders = $result['total_orders'] ?? 0;
$profit = $result['profit'] ?? 0;
$average_order = ($total_orders > 0) ? ($total_sales / $total_orders) : 0;

$sql_orders = "SELECT o.*, p.name AS product_name, c.name AS customer_name, c.gender, 
                     (o.total_amount * 0.15) AS order_profit
               FROM orders o
               JOIN products p ON o.product_id = p.id
               JOIN customers c ON o.customer_id = c.id
               $where
               ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql_orders);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();

$sql_top = "SELECT p.name, 
                  SUM(o.quantity) as total_sold, 
                  SUM(o.total_amount) as product_sales, 
                  SUM(o.total_amount * 0.15) as product_profit
           FROM orders o
           JOIN products p ON o.product_id = p.id
           JOIN customers c ON o.customer_id = c.id
           $where
           GROUP BY p.id
           ORDER BY total_sold DESC";
$stmt = $conn->prepare($sql_top);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$top_products = $stmt->get_result();
$stmt->close();
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
        }
		.filter-toggle-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 5px;
    cursor: pointer;
    float: right;
    margin-bottom: 15px;
}

.filter-panel {
    position: fixed;
    top: 0;
    right: -320px;
    width: 300px;
    height: 100%;
    background-color: #ffffff;
    box-shadow: -2px 0 10px rgba(0,0,0,0.2);
    padding: 20px;
    overflow-y: auto;
    transition: right 0.3s ease;
    z-index: 1000;
}

.filter-panel.open {
    right: 0;
}

.filter-panel h3 {
    margin-top: 0;
    color: #1f2937;
    text-align: center;
}

.filter-panel form {
    display: flex;
    flex-direction: column;
}

.filter-panel select,
.filter-panel input,
.filter-panel button {
    margin: 10px 0;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.filter-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.4);
    z-index: 900;
}

.filter-overlay.show {
    display: block;
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
        header {
            background-color: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
        }
        form {
            text-align: center;
            margin-bottom: 25px;
        }
        form select, form input, form button {
            margin: 5px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .summary {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .card {
            flex: 1 1 200px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            text-align: center;
        }
        .card h3 {
            margin-bottom: 10px;
            color: #007bff;
        }
        .card p {
            font-size: 22px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .back-btn {
            display: block;
            width: max-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #1f2937;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin dashboard.php">🏠 Dashboard</a>
    <a href="admin_orders.php">🧾 Orders</a>
    <a href="manage_products.php">🖥️ Products</a>
    <a href="admin_add_product.php">➕ Add Product</a>
    <a href="manage_Customers.php">👥 Customers</a>
    <a href="admin_view_requests.php">📢 Marketing</a>
    <a href="admin_view_contacts.php">📨 Contacts</a>
    <a href="sales.php" class="active">💰 Sales</a>
    <a href="admin_logout.php" style="color: red;">🚪 Logout</a>
</div>

<div class="content">
    <header>
        <h2>Sales Report - Global Computer Ltd</h2>
    </header>

   <button class="filter-toggle-btn" onclick="toggleFilterPanel()">🔍 Filter</button>

<div class="filter-overlay" id="filterOverlay" onclick="toggleFilterPanel()"></div>

<div class="filter-panel" id="filterPanel">
    <h3>Filter Sales</h3>
    <form method="get">
        <label>Period</label>
        <select name="period">
            <option value="">-- Select --</option>
            <option value="today" <?= ($period == 'today') ? 'selected' : '' ?>>Today</option>
            <option value="week" <?= ($period == 'week') ? 'selected' : '' ?>>This Week</option>
            <option value="month" <?= ($period == 'month') ? 'selected' : '' ?>>This Month</option>
        </select>

        <label>Start Date</label>
        <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">

        <label>End Date</label>
        <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">

        <label>Gender</label>
        <select name="gender">
            <option value="">-- All --</option>
            <option value="male" <?= ($gender == 'male') ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= ($gender == 'female') ? 'selected' : '' ?>>Female</option>
        </select>

        <button type="submit">Apply Filters</button>
    </form>
</div>

    <div class="summary">
        <div class="card">
            <h3>Total Sales</h3>
            <p>KES <?= number_format($total_sales, 2) ?></p>
        </div>
        <div class="card">
            <h3>Total Orders</h3>
            <p><?= $total_orders ?></p>
        </div>
        <div class="card">
            <h3>Average Order</h3>
            <p>KES <?= number_format($average_order, 2) ?></p>
        </div>
        <div class="card">
            <h3>Net Profit</h3>
            <p>KES <?= number_format($profit, 2) ?></p>
        </div>
    </div>

    <h3>Approved Orders</h3>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Gender</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total (KES)</th>
            <th>Date</th>
        </tr>
        <?php if ($orders->num_rows > 0): ?>
            <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= number_format($row['total_amount'], 2) ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">No approved orders found.</td></tr>
        <?php endif; ?>
    </table>

    <h3>Top Products</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Total Sold</th>
        </tr>
        <?php if ($top_products->num_rows > 0): ?>
            <?php while ($prod = $top_products->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($prod['name']) ?></td>
                    <td><?= $prod['total_sold'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="2">No product sales data.</td></tr>
        <?php endif; ?>
    </table>

    <a href="admin dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

    <footer>
        &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
    </footer>
</div>
<script>
    function toggleFilterPanel() {
        const panel = document.getElementById('filterPanel');
        const overlay = document.getElementById('filterOverlay');
        panel.classList.toggle('open');
        overlay.classList.toggle('show');
    }
</script>

</body>
</html>
