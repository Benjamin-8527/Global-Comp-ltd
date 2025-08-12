<?php
session_start();
include 'db_connect.php';

// Simulate a logged-in customer (for testing only)
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['customer_id'] = 1; // Make sure this ID exists in your DB
    $_SESSION['customer_name'] = 'Test User';
}

$customer_id = $_SESSION['customer_id'];
$message = "";

// Handle update request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($name) && !empty($email)) {
        $sql = "UPDATE customers SET name=?, email=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $password, $customer_id);
        $stmt->execute();
        $message = "✅ Profile updated successfully.";
        $_SESSION['customer_name'] = $name;
    } else {
        $message = "⚠️ All fields are required.";
    }
}

// Fetch customer details
$stmt = $conn->prepare("SELECT * FROM customers WHERE id=?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("⚠️ Error: Customer with ID $customer_id not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            background-color: #1f2937;
            padding-top: 20px;
        }

        .sidebar h2 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
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

        .sidebar a.active {
            background-color: #2563eb;
        }

        .main-content {
            margin-left: 220px;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1, h2 {
            text-align: center;
            color: #007bff;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            color: green;
            margin-top: 10px;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            background-color: #f0f0f0;
            padding: 10px 15px;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .back-link:hover {
            background-color: #ddd;
        }

        footer {
            background-color: #1f2937;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            margin-left: 220px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Customer</h2>
    <a href="customer_dashboard.php">🏠 Dashboard</a>
    <a href="place order.php">🛒 Place Order</a>
    <a href="my_orders.php">📄 My Orders</a>
    <a href="marketing_request.php">📢 Marketing Request</a>
    <a href="update_profile.php" class="active">👤 Update Profile</a>
    <a href="customer_logout.php" style="color: red;">🚪 Logout</a>
</div>

<div class="main-content">
    <h1>Update Profile</h1>

    <div class="container">
        <form method="post">
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" placeholder="Name" required>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Email" required>
            <input type="text" name="password" value="<?= htmlspecialchars($user['password']) ?>" placeholder="Password" required>
            <input type="submit" value="Update">
        </form>

        <?php if (!empty($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <a href="customer_dashboard.php" class="back-link">⬅ Back to Dashboard</a>
    </div>
</div>

<footer>
    &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
</footer>

</body>
</html>
