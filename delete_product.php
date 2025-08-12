<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Check if the product ID is set in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = (int) $_GET['id'];

    // Optional: delete image file if needed (if storing image paths)
    // $result = $conn->query("SELECT image FROM products WHERE id = $product_id");
    // if ($result && $row = $result->fetch_assoc()) {
    //     $image_path = 'uploads/' . $row['image'];
    //     if (file_exists($image_path)) {
    //         unlink($image_path); // Delete the image file
    //     }
    // }

    // Delete product from database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: manage_products.php?message=Product+deleted+successfully");
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid product ID.";
}

$conn->close();
?>
