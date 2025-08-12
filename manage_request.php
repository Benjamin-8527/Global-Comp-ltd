<?php
session_start();
$conn = new mysqli("localhost", "root", "", "computer_store");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM marketing_requests ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Marketing Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px #ccc;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #333;
            color: #fff;
        }
        a.approve {
            color: green;
            margin-right: 10px;
        }
        a.decline {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Manage Marketing Requests</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Service</th>
            <th>Message</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['service']); ?></td>
                <td><?= htmlspecialchars($row['message']); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
                <td>
                    <a class="approve" href="approve_decline_request.php?id=<?= urlencode($row['id']); ?>&action=approve" onclick="return confirm('Approve this request?');">Approve</a>
                    <a class="decline" href="approve_decline_request.php?id=<?= urlencode($row['id']); ?>&action=decline" onclick="return confirm('Decline this request?');">Decline</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
