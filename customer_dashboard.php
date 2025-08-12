<?php
session_start();
if (!isset($_SESSION['customer_logged_in'])) {
    header("Location: login.php");
    exit();
}

$customer_name = $_SESSION['customer_name'] ?? '';

$conn = new mysqli("localhost", "root", "", "computer_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard - Global Computers</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f2f6fa;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #0a3d62;
            padding-top: 30px;
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #07528c;
        }

        .main-content {
            margin-left: 240px;
            padding: 30px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar h2 {
            margin: 0;
            color: #0a3d62;
        }

        .logout {
            background-color: #dc3545;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.3s;
        }

        .logout:hover {
            background-color: #a71d2a;
        }

        .actions {
            margin: 30px 0 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .actions a {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
            transition: background 0.3s;
        }

        .actions a:hover {
            background-color: #0056b3;
        }

        h3 {
            margin-top: 40px;
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product {
            background: #fafafa;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: calc(25% - 20px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 15px;
            text-align: center;
            transition: transform 0.3s;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            max-width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product h4 {
            margin: 10px 0 5px;
            color: #007bff;
        }

        .product p {
            font-size: 14px;
            color: #555;
        }

        .product strong {
            display: block;
            margin-top: 8px;
            font-weight: bold;
            color: #333;
        }

        .back-home {
            display: inline-block;
            margin-top: 40px;
            background-color:  #007bff;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
        }

        .back-home:hover {
            background-color:  #007bff;
        }

        @media (max-width: 992px) {
            .product {
                width: calc(33.333% - 20px);
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .product {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .product {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Customer Panel</h2>
    <a href="customer_dashboard.php" class="active">🏠 Dashboard</a>
    <a href="place order.php">📦 Place Order</a>
    <a href="my_orders.php">📄 My Orders</a>
    <a href="marketing_request.php">📢 Marketing Request</a>
    <a href="update_profile.php">👤 Update Profile</a>
    <a href="logout.php" style="color: red;">🚪 Logout</a>
</div>

<div class="main-content">
    <div class="top-bar">
        <h2>Welcome! <?php echo htmlspecialchars($customer_name); ?></h2>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="actions">
        <a href="place order.php">📦 Place Order</a>
        <a href="my_orders.php">📄 View Orders</a>
        <a href="marketing_request.php">📢 Request Marketing</a>
        <a href="update_profile.php">👤 Update Profile</a>
    </div>

    <h3>🛒 Available Products</h3>
    <div class="products-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image">
                    <?php else: ?>
                        <img src="no-image.png" alt="No image">
                    <?php endif; ?>
                    <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <strong>KES <?php echo number_format($row['price'], 2); ?></strong>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products available at the moment.</p>
        <?php endif; ?>
    </div>

    <a href="index.html" class="back-home">⬅ Back to Home Page</a>
</div>

</body>
</html>

