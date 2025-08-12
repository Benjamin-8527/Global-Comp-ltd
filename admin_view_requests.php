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

$status_filter = $_GET['status'] ?? '';

$sql = "SELECT * FROM marketing_requests";
if ($status_filter) {
    $sql .= " WHERE status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marketing Requests - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 220px;
            background-color: #1f2937;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #2563eb;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
            width: 100%;
        }
        h1, h2 {
            text-align: center;
            color: #007bff;
        }
        .filter-form {
            text-align: center;
            margin: 20px 0;
        }
        .filter-form select {
            padding: 8px;
            margin-left: 10px;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
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
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        .approve {
            background-color: #28a745;
        }
        .decline {
            background-color: #dc3545;
        }
        .btn:hover {
            opacity: 0.9;
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
    <a href="admin_view_requests.php" class="active">📢 Marketing</a>
    <a href="admin_view_contacts.php">📨 Contacts</a>
    <a href="sales.php">💰 Sales</a>
    <a href="admin_logout.php" style="color: red;">🚪 Logout</a>
</div>

<div class="main-content">
    <h1>Marketing Requests</h1>

    <form class="filter-form" method="GET">
        <label>Status:</label>
        <select name="status" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Approved" <?= $status_filter == 'Approved' ? 'selected' : '' ?>>Approved</option>
            <option value="Declined" <?= $status_filter == 'Declined' ? 'selected' : '' ?>>Declined</option>
        </select>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Business</th>
            <th>Owner</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['business_name']) ?></td>
                    <td><?= htmlspecialchars($row['owner_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <form method="post" action="approve_decline_request.php" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                        </form>
                        <form method="post" action="approve_decline_request.php" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="decline" class="btn decline">Decline</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No marketing requests found.</td></tr>
        <?php endif; ?>
    </table>

    <footer>
        &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
    </footer>
</div>

</body>
</html>
