<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$message = "";
$image_path = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $filename = basename($_FILES["image"]["name"]);
        $image_path = $target_dir . time() . "_" . $filename;
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, image, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $product_name, $description, $image_path, $price);

    if ($stmt->execute()) {
        $message = "✅ Product added successfully!";
    } else {
        $message = "❌ Error: Could not add product.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Admin Panel | Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
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

        .main-content {
            margin-left: 250px;
            padding: 30px;
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

        .form-container {
            background: white;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
        }

        .product-image {
            display: block;
            margin: 20px auto;
            max-width: 250px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        footer {
            background-color: #1f2937;
            color: white;
            text-align: center;
            padding: 20px;
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
                padding: 20px;
            }

            input, textarea {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin dashboard.php">🏠 Dashboard</a>
    <a href="manage_products.php">🖥️ Manage Products</a>
    <a href="admin_add_product.php">➕ Add Product</a>
    <a href="admin_orders.php">📦 Orders</a>
    <a href="admin_view_requests.php">📢 Marketing</a>
    <a href="manage_Customers.php">👥 Customers</a>
    <a href="admin_view_contacts.php">📩 Contact</a>
    <a href="sales.php">💰 Sales</a>
    <a href="admin_logout.php" class="logout-button">🚪 Logout</a>
</div>

<div class="main-content">
    <header>
        <h1>Global Computer Ltd - Add Product</h1>
    </header>

    <div class="form-container">
        <h2>Add New Product</h2>

        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($image_path && $message === "✅ Product added successfully!"): ?>
            <img src="<?= htmlspecialchars($image_path) ?>" alt="Uploaded Product Image" class="product-image">
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="price">Price (KES):</label>
            <input type="number" step="0.01" name="price" id="price" placeholder="e.g., 50000.00" required>

            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <input type="submit" value="Add Product">
        </form>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
    </footer>
</div>

</body>
</html>
