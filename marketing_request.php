<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$conn = new mysqli("localhost", "root", "", "computer_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $business = $_POST['business_name'] ?? '';
    $owner = $_POST['owner_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $website = $_POST['website'] ?? '';
    $platform = $_POST['platform_preference'] ?? '';
    $desc = $_POST['description'] ?? '';

    if ($business && $owner && $email && $phone && $location && $desc) {
        $stmt = $conn->prepare("INSERT INTO marketing_requests (business_name, owner_name, email, phone, location, website, platform_preference, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $business, $owner, $email, $phone, $location, $website, $platform, $desc);
        $stmt->execute();
        $stmt->close();
        $success = true;
    } else {
        $error = "All fields marked * are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marketing Request - Global Computer Ltd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary: #007bff;
            --dark: #1f2937;
            --light-bg: #f4f6f9;
            --white: #ffffff;
            --danger: #dc3545;
            --success: #28a745;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: var(--light-bg);
            color: #333;
            display: flex;
        }

        .sidebar {
            width: 220px;
            background-color: #1f2937;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 30px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: var(--primary);
        }

        .main-content {
            margin-left: 220px;
            padding: 30px;
            flex-grow: 1;
        }

        header {
            background-color: var(--dark);
            color: white;
            padding: 25px 15px;
            text-align: center;
        }

        .form-container {
            max-width: 720px;
            margin: auto;
            background: var(--white);
            padding: 35px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .form-container h2 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 25px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: var(--primary);
            color: white;
            padding: 12px 20px;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            color: var(--success);
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .error {
            color: var(--danger);
            text-align: center;
            margin-bottom: 20px;
        }

        .back-button {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            margin-top: 15px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: var(--dark);
            color: #ccc;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Global Computer</h2>
    <a href="customer_dashboard.php">🏠 Dashboard</a>
    <a href="place_order.php">🛒 Place Order</a>
    <a href="my_orders.php">📄 My Orders</a>
    <a href="update_profile.php">👤 Update Profile</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">
    <header>
        <h1>Marketing Service Request</h1>
    </header>

    <div class="form-container">
        <h2>Request Marketing Assistance</h2>

        <?php if ($success): ?>
            <p class="message">✅ Request sent successfully! Redirecting...</p>
            <script>setTimeout(() => window.location.href = "customer_dashboard.php", 3000);</script>
        <?php elseif (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="business_name" placeholder="Business Name *" required>
            <input type="text" name="owner_name" placeholder="Owner's Full Name *" required>
            <input type="email" name="email" placeholder="Email Address *" required>
            <input type="text" name="phone" placeholder="Phone Number *" required>
            <input type="text" name="location" placeholder="Business Location *" required>
            <input type="text" name="website" placeholder="Website (Optional)">
            <input type="text" name="platform_preference" placeholder="Preferred Platform (e.g., Facebook, Instagram)">
            <textarea name="description" placeholder="Brief Description of Your Business *" required></textarea>
            <button type="submit">Submit Request</button>
        </form>

        <a href="customer_dashboard.php" class="back-button">⬅ Back to Dashboard</a>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
    </footer>
</div>

</body>
</html>
