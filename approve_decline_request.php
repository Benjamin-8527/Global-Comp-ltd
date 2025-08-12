<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "computer_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve' || $action === 'decline') {
        $status = $action === 'approve' ? 'Approved' : 'Declined';

        $stmt = $conn->prepare("UPDATE marketing_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            echo "<script>
                alert('Request has been $status successfully.');
                window.location.href = 'manage_requests.php';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('Failed to update request.');
                window.location.href = 'manage_requests.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Invalid action!');
            window.location.href = 'manage_requests.php';
        </script>";
        exit();
    }
} else {
    echo "<script>
        alert('Invalid request!');
        window.location.href = 'manage_requests.php';
    </script>";
    exit();
}

$conn->close();
?>
