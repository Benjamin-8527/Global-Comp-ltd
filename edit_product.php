 <?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID not specified.");
}

$product_id = intval($_GET['id']);
$message = "";
$image_path = "";

$result = $conn->query("SELECT * FROM products WHERE id = $product_id");
if ($result->num_rows === 0) {
    die("Product not found.");
}
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $filename = basename($_FILES["image"]["name"]);
        $new_image_path = $target_dir . time() . "_" . $filename;
        move_uploaded_file($_FILES["image"]["tmp_name"], $new_image_path);

        // Update with new image
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
        $stmt->bind_param("ssdsi", $product_name, $description, $price, $new_image_path, $product_id);
        $image_path = $new_image_path;

    } else {
        // Update without changing image
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=? WHERE id=?");
        $stmt->bind_param("ssdi", $product_name, $description, $price, $product_id);
        $image_path = $product['image'];
    }

    if ($stmt->execute()) {
        $message = "✅ Product updated successfully!";
        // Refresh product data
        $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
        $product = $result->fetch_assoc();
    } else {
        $message = "❌ Error: Could not update product.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - Admin Panel | Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #374151;
            padding: 10px 0;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            font-size: 15px;
            margin: 0 10px;
            display: inline-block;
        }

        nav a:hover {
            background-color: #2563eb;
            border-radius: 5px;
        }

        .form-container {
            background: white;
            padding: 30px;
            margin: 40px auto;
            max-width: 600px;
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
        }
    </style>
</head>
<body>

<header>
    <h1>Global Computer Ltd - Admin Panel</h1>
</header>

<nav>
    <a href="admin dashboard.php">🏠 Dashboard</a>
    <a href="manage_products.php">🖥️ Manage Products</a>
    <a href="admin_orders.php">📦 Orders</a>
    <a href="admin_view_requests.php">📢 Marketing</a>
    <a href="manage_Customers.php">👥 Customers</a>
    <a href="admin_view_contacts.php">📩 Contact</a>
    <a href="sales.php">💰 Sales</a>
    <a href="admin_logout.php" style="color: red;">🚪 Logout</a>
</nav>

<div class="form-container">
    <h2>Edit Product</h2>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($product['image'])): ?>
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-image">
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" id="product_name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label for="price">Price (KES):</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label for="image">Change Product Image (optional):</label>
        <input type="file" name="image" id="image" accept="image/*">

        <input type="submit" value="Update Product">
    </form>
</div>

<a class="back-link" href="manage_products.php" style="display: block; text-align: center; margin: 20px; text-decoration: none;">⬅ Back to Products</a>

<footer>
    &copy; <?= date("Y") ?> Global Computer Ltd | All Rights Reserved.
</footer>

</body>
</html>
