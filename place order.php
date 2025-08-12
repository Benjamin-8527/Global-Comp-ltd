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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $customer_id = $_SESSION['customer_id'];

    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    $total = $price * $quantity;

    $stmt = $conn->prepare("INSERT INTO orders (customer_id, product_id, quantity, total_amount, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("iiid", $customer_id, $product_id, $quantity, $total);
    if ($stmt->execute()) {
        $message = "✅ Order placed successfully!";
    } else {
        $message = "❌ Failed to place order.";
    }
    $stmt->close();
}

$products = $conn->query("SELECT id, name, price FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Order - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 220px;
            background-color: #1f2937;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            padding-top: 30px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #2563eb;
        }

        .sidebar a.active {
            background-color: #2563eb;
        }

        .main {
            margin-left: 220px;
            flex-grow: 1;
            padding: 30px;
        }

        header {
            background-color: #111827;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2563eb;
        }

        label {
            font-weight: bold;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            background-color: #2563eb;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: blue;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: green;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2563eb;
            background-color: #f0f0f0;
            padding: 10px 15px;
            border-radius: 6px;
        }

        .back-link:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Customer Panel</h2>
    <a href="customer_dashboard.php">🏠 Dashboard</a>
    <a href="place_order.php" class="active">🛒 Place Order</a>
    <a href="my_orders.php">📄 My Orders</a>
    <a href="update_profile.php">👤 Update Profile</a>
    <a href="logout.php" style="color: red;">🚪 Logout</a>
</div>

<div class="main">
    <header>
        <h1>Global Computer Ltd</h1>
    </header>

    <div class="container">
        <h2>Place Order</h2>

        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Product:</label>
            <select name="product_id" required>
                <?php while($row = $products->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> - KES <?= number_format($row['price'], 2) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Quantity:</label>
            <input type="number" name="quantity" min="1" required>

            <button type="submit">Place Order</button>
        </form>

        <a href="customer_dashboard.php" class="back-link">⬅ Back to Dashboard</a>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
    </footer>
</div>

</body>
</html>
