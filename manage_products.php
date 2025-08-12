<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #1f2937;
            min-height: 100vh;
            color: white;
            padding-top: 30px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #2563eb;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }

        h2 {
            color: #007bff;
            margin-top: 10px;
        }

        .message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px #ccc;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .action-buttons a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .action-buttons a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .back-link:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #1f2937;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            position: relative;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin dashboard.php">🏠 Dashboard</a>
    <a href="admin_add_product.php">➕ Add Product</a>
    <a href="admin_orders.php">📦 Orders</a>
    <a href="admin_view_requests.php">📢 Marketing</a>
    <a href="manage_Customers.php">👥 Customers</a>
    <a href="admin_view_contacts.php">📨 Contacts</a>
    <a href="sales.php">💰 Sales</a>
    <a href="admin_logout.php" style="color: red;">🚪 Logout</a>
</div>

<div class="content">
    <h2>Manage Products</h2>

    <?php if (isset($_GET['message'])): ?>
        <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price (KES)</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['price']) ?></td>
            <td class="action-buttons">
                <a href="edit_product.php?id=<?= $row['id'] ?>">✏️ Edit</a>
                <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');">🗑️ Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a class="back-link" href="admin dashboard.php">⬅ Back to Dashboard</a>

    <footer>
        &copy; 2025 Global Computer Ltd | Admin Panel
    </footer>
</div>

</body>
</html>
