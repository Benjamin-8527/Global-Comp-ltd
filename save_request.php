<?php
$conn = new mysqli("localhost", "root", "", "computer_store");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$business_name = $_POST['business_name'];
$owner_name = $_POST['owner_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$business_type = $_POST['business_type'];
$platforms = implode(", ", $_POST['platforms']);
$message = $_POST['message'];

$sql = "INSERT INTO marketing_requests (business_name, owner_name, email, phone, business_type, platforms, message)
        VALUES ('$business_name', '$owner_name', '$email', '$phone', '$business_type', '$platforms', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "Request submitted successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
