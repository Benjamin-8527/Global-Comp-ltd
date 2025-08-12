<?php
// Start session if needed (optional, for user feedback or future use)
session_start();

// Include your database connection
include 'db_connect.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and get form data
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $message = trim($_POST["message"] ?? '');

    // Validate input
    if (empty($name) || empty($email) || empty($message)) {
        echo "<p style='color: red;'>Please fill in all fields.</p>";
        exit;
    }

    // Prepare and insert message into database
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Your message has been sent successfully.</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to send message: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p style='color: red;'>Invalid request.</p>";
}
?>
