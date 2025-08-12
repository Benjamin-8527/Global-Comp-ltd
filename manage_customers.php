<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_customer_id'])) {
        $customer_id = intval($_POST['delete_customer_id']);

        $orderCheck = $conn->prepare("SELECT COUNT(*) FROM orders WHERE customer_id = ?");
        $orderCheck->bind_param("i", $customer_id);
        $orderCheck->execute();
        $orderCheck->bind_result($orderCount);
        $orderCheck->fetch();
        $orderCheck->close();

        if ($orderCount > 0) {
            echo "<script>alert('❌ Cannot delete customer because they have existing orders.');</script>";
        } else {
            $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $stmt->close();
            echo "<script>alert('✅ Customer deleted successfully.');</script>";
        }
    }
}

$result = $conn->query("SELECT * FROM customers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 240px;
            background-color: #1f2937;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #374151;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: #2563eb;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
            width: calc(100% - 240px);
        }
        header {
            background-color: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .action-btn {
            padding: 7px 12px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .delete {
            background-color: #dc3545;
        }
        .action-btn:hover {
            opacity: 0.9;
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

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="admin dashboard.php">🏠 Dashboard</a>
    <a href="manage_products.php">🖥️ Products</a>
    <a href="admin_add_product.php">➕ Add Product</a>
    <a href="admin_orders.php">📦 Orders</a>
    <a href="admin_view_requests.php">📢 Marketing</a>
    <a href="admin_view_contacts.php">📩 Contacts</a>
    <a href="sales.php">💰 Sales</a>
    <a href="admin_customers.php" style="background-color: #2563eb;">👥 Customers</a>
    <a href="admin_logout.php" style="color: red;">🚪 Logout</a>
</div>

<div class="main-content">
    <header>
        <h1>Global Computer Ltd - Admin Panel</h1>
    </header>

    <h2>Manage Customers</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <form method="post" style="display:inline-block" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                        <input type="hidden" name="delete_customer_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="action-btn delete">🗑️ Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="admin dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

    <footer>
        &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
    </footer>
</div>

</body>
</html>
