<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "computer_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orders    = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$marketing = $conn->query("SELECT COUNT(*) as count FROM marketing_requests")->fetch_assoc()['count'];
$customers = $conn->query("SELECT COUNT(*) as count FROM customers")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

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

        .summary {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .summary .card {
            flex: 1 1 250px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .summary .card:hover {
            transform: translateY(-5px);
        }

        .summary h3 {
            margin-bottom: 10px;
            color: #007bff;
        }

        .summary p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .card.manage {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card.manage h3 {
            margin-bottom: 20px;
            color: #007bff;
        }

        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .button-group a {
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 12px 18px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .button-group a:hover {
            background-color: #0056b3;
        }

        .back-home {
            display: inline-block;
            margin-top: 30px;
            background: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-home:hover {
            background: #218838;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #1f2937;
            color: white;
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

            .button-group {
                flex-direction: column;
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
    <a href="admin_view_contacts.php">📨 Contacts</a>
    <a href="sales.php">💰 Sales</a>
    <a href="admin logout.php" class="logout-button">Logout</a>
</div>

<div class="main-content">
    <header>
        <h2>Welcome to Admin Dashboard</h2>
    </header>

    <div class="summary">
        <div class="card">
            <h3>Total Orders</h3>
            <p><?= $orders ?></p>
        </div>
        <div class="card">
            <h3>Marketing Requests</h3>
            <p><?= $marketing ?></p>
        </div>
        <div class="card">
            <h3>Total Customers</h3>
            <p><?= $customers ?></p>
        </div>
    </div>

    <div class="card manage">
        <h3>Manage Site</h3>
        <div class="button-group">
            <a href="admin_orders.php">🧾 View Orders</a>
            <a href="admin_view_requests.php">📢 Marketing Requests</a>
            <a href="manage_products.php">🖥️ Manage Products</a>
            <a href="admin_add_product.php">➕ Add Product</a>
            <a href="manage_Customers.php">👥 Manage Customers</a>
            <a href="admin_view_contacts.php">📨 View Contact Messages</a>
            <a href="sales.php">💰 Sales</a>
        </div>
    </div>

    <a href="index.html" class="back-home">⬅ Back to Home Page</a>

    <footer>
        &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
    </footer>
</div>

</body>
</html>

