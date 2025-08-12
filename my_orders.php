<?php
session_start();
if (!isset($_SESSION['customer_logged_in'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "computer_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_id = $_SESSION['customer_id'];

$stmt = $conn->prepare("SELECT o.*, p.name AS product_name FROM orders o JOIN products p ON o.product_id = p.id WHERE o.customer_id = ? ORDER BY o.created_at DESC");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - Global Computer Ltd</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 230px;
            height: 100vh;
            background-color: #1f2937;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 40px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #2563eb;
        }

        .main-content {
            margin-left: 230px;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        table {
            width: 95%;
            margin: 30px auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px 15px;
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

        .download-btn {
            padding: 7px 13px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .download-btn:hover {
            background-color: #1e7e34;
        }

        .back-btn {
            display: block;
            width: max-content;
            margin: 25px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #1f2937;
            color: white;
            margin-top: 260px;
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 25px;
            border: 1px solid #888;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }

        .modal-content h3 {
            margin-bottom: 20px;
            color: #007bff;
        }

        .theme-btn {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .light-btn {
            background-color: #28a745;
        }

        .dark-btn {
            background-color: #343a40;
        }

        .theme-btn:hover {
            opacity: 0.85;
        }
    </style>
    <script>
        function showModal(orderId) {
            document.getElementById('modal').style.display = 'block';
            document.getElementById('light-link').href = "generate_invoice.php?order_id=" + orderId + "&theme=light";
            document.getElementById('dark-link').href = "generate_invoice.php?order_id=" + orderId + "&theme=dark";
        }
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
        window.onclick = function(event) {
            let modal = document.getElementById('modal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</head>
<body>

<div class="sidebar">
    <h2>Customer Panel</h2>
    <a href="customer_dashboard.php">🏠 Dashboard</a>
    <a href="my_orders.php">📦 My Orders</a>
    <a href="update_profile.php">👤 Update Profile</a>
    <a href="logout.php" style="color: red;">🚪 Logout</a>
</div>

<div class="main-content">
    <h1>My Orders</h1>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total (KES)</th>
            <th>Status</th>
            <th>Invoice</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= number_format($row['total_amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <button class="download-btn" onclick="showModal(<?= $row['id'] ?>)">Download</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No orders found.</td></tr>
        <?php endif; ?>
    </table>

    <a href="customer_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<footer>
    &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
</footer>

<!-- Modal for theme choice -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h3>Select Invoice Theme</h3>
        <a id="light-link" class="theme-btn light-btn" onclick="closeModal()">Light Theme</a>
        <a id="dark-link" class="theme-btn dark-btn" onclick="closeModal()">Dark Theme</a>
    </div>
</div>

</body>
</html>
