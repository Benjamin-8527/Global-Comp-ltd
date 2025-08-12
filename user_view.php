<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Computers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        input[type="number"] {
            width: 60px;
            padding: 5px;
            margin-top: 5px;
        }
        input[type="submit"] {
            padding: 6px 12px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            margin-top: 10px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #004999;
        }
        h2 {
            color: #333;
        }
    </style>
</head>
<body>

<?php
$conn = new mysqli("localhost", "root", "", "computer_store");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$result = $conn->query("SELECT * FROM products");

if ($result->num_rows > 0) {
    echo "<h2>Available Computers</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<form method='post' action='add_to_cart.php'>";
        echo "<b>{$row['name']}</b> - Ksh {$row['price']} <br>";
        echo "Quantity: <input type='number' name='qty' min='1' max='{$row['stock']}' required><br>";
        echo "<input type='hidden' name='product_id' value='{$row['id']}'>";
        echo "<input type='submit' value='Add to Cart'>";
        echo "</form>";
    }
} else {
    echo "<p>No computers available at the moment.</p>";
}

$conn->close();
?>

</body>
</html>
