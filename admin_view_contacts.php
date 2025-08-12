<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connect.php';
$result = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f4f6f9;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #1f2937;
            padding-top: 20px;
            position: fixed;
            height: 100%;
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
        }

        .sidebar a {
            display: block;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 20px;
            margin: 6px 12px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #2563eb;
        }

        .logout-button {
            background-color: #dc3545;
            display: block;
            text-align: center;
            margin: 20px auto;
            padding: 10px 16px;
            border-radius: 5px;
            text-decoration: none;
            width: 80%;
        }

        .logout-button:hover {
            background-color: #a71d2a;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }

        header {
            background-color: #374151;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            margin-top: 10px;
            color: #007bff;
        }

        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .back-link {
            text-align: center;
            margin: 20px 0;
        }

        .back-link a {
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .back-link a:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #1f2937;
            color: #ccc;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: static;
                height: auto;
            }

            .main-content {
                margin-left: 0;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin dashboard.php">🏠 Dashboard</a>
    <a href="admin_orders.php">🧾 Orders</a>
    <a href="admin_view_requests.php">📢 Marketing</a>
    <a href="manage_products.php">🖥️ Products</a>
    <a href="admin_add_product.php">➕ Add Product</a>
    <a href="manage_Customers.php">👥 Customers</a>
    <a href="admin_view_contacts.php">📨 Contact Messages</a>
    <a href="sales.php">💰 Sales</a>
    <a href="admin logout.php" class="logout-button">Logout</a>
</div>

<div class="main-content">
    <header>
        <h1>Global Computer Ltd</h1>
        <p>Admin - Contact Messages</p>
    </header>

    <h2>Customer Contact Messages</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Submitted At</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= $row['submitted_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="back-link">
        <a href="admin dashboard.php">← Back to Dashboard</a>
    </div>

    <footer>
        &copy; <?= date('Y') ?> Global Computer Ltd. All Rights Reserved.
    </footer>
</div>

</body>
</html>
