<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to send email to customer
function sendCustomerNotification($customer_email, $customer_name, $subject, $bodyMessage) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'benngige200@gmail.com';      // Your Gmail
        $mail->Password   = 'qjtr nsqf vnvl ownl';           // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('benngige200@gmail.com', 'Global Computer Ltd');
        $mail->addAddress($customer_email, $customer_name);

        $mail->isHTML(true);
        $mail->Subject = $subject;

        $mail->Body = "
            <p>Dear <strong>$customer_name</strong>,</p>
            <p>$bodyMessage</p>
            <p>Best regards,<br>Global Computer Ltd</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
        return false;
    }
}

// Example usage when admin performs an action:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'], $_POST['customer_email'], $_POST['customer_name'])) {
    $id            = intval($_POST['id']);
    $status        = $_POST['status'];
    $customer_email = $_POST['customer_email'];
    $customer_name = $_POST['customer_name'];

    $allowed = ['approved', 'declined'];

    if (in_array($status, $allowed)) {
        // Update the order/request status in DB
        $conn = new mysqli("localhost", "root", "", "computer_store");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();

        // Compose email message
        $subject = "Update on your request (Order ID: $id)";
        $bodyMessage = "We would like to inform you that your request (Order ID: $id) has been <strong>$status</strong>.";

        // Send email to customer
        if (sendCustomerNotification($customer_email, $customer_name, $subject, $bodyMessage)) {
            echo "✅ Action completed and email sent to customer.";
        } else {
            echo "⚠ Action completed but failed to send email.";
        }
    }
}
?>
