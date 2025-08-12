<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "computer_store"); // Replace with your actual DB name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: admin_view_requests.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = $_POST['status'];
    $allowed = ['Approved', 'Declined'];

    if (in_array($status, $allowed)) {
        $stmt = $conn->prepare("UPDATE marketing_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: admin_view_requests.php");
        exit();
    }
}

// Fetch request details
$stmt = $conn->prepare("SELECT * FROM marketing_requests WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();
$stmt->close();

if (!$request) {
    echo "<h3>No request found for ID $id.</h3>";
    echo "<a href='admin_view_requests.php'>Back to Requests</a>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Marketing Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        .field {
            margin-bottom: 15px;
        }

        .field label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .actions {
            margin-top: 20px;
        }

        .btn {
            padding: 8px 16px;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn.back {
            background-color: #6c757d;
        }

        .btn.approve {
            background-color: #28a745;
        }

        .btn.decline {
            background-color: #dc3545;
        }
    </style>
</head>
<body>

<nav>
    <div>Admin Panel</div>
    <div>
        <a href="admin dashboard.php">Dashboard</a>
        <a href="logout.php" class="btn decline" style="padding: 6px 10px;">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Request Details</h2>

    <div class="field">
        <label>Business Name:</label>
        <div><?= htmlspecialchars($request['business_name']) ?></div>
    </div>

    <div class="field">
        <label>Owner Name:</label>
        <div><?= htmlspecialchars($request['owner_name']) ?></div>
    </div>

    <div class="field">
        <label>Email:</label>
        <div><?= htmlspecialchars($request['email']) ?></div>
    </div>

    <div class="field">
        <label>Phone:</label>
        <div><?= htmlspecialchars($request['phone']) ?></div>
    </div>

    <div class="field">
        <label>Location:</label>
        <div><?= htmlspecialchars($request['location']) ?></div>
    </div>

    <div class="field">
        <label>Message:</label>
        <div><?= nl2br(htmlspecialchars($request['message'])) ?></div>
    </div>

    <div class="field">
        <label>Date Submitted:</label>
        <div><?= htmlspecialchars($request['created_at']) ?></div>
    </div>

    <div class="field">
        <label>Status:</label>
        <strong><?= htmlspecialchars($request['status']) ?></strong>
    </div>

    <div class="actions">
        <form method="post" style="display:inline;">
            <input type="hidden" name="status" value="Approved">
            <button type="submit" class="btn approve">Approve</button>
        </form>

        <form method="post" style="display:inline;">
            <input type="hidden" name="status" value="Declined">
            <button type="submit" class="btn decline">Decline</button>
        </form>

        <a href="admin_view_requests.p
