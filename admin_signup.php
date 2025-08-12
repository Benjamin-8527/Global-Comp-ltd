<?php
include 'db_connect.php';

$message = "";

// Check if an admin already exists
$check_sql = "SELECT COUNT(*) AS total FROM admins";
$check_result = $conn->query($check_sql);
$row = $check_result->fetch_assoc();

if ($row['total'] > 0) {
    // Redirect to login if admin already exists
    header("Location: admin_login.php");
    exit();
}

// Only run signup if no admin exists
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['id']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admins (id, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $id, $email, $hashedPassword);

    if ($stmt->execute()) {
        $message = "Admin account created. Redirecting to login...";
        header("refresh:2;url=admin_login.php");
    } else {
        $message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Sign Up</title>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial;
        }
        .box {
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 10px #aaa;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            width: 90%;
            margin-bottom: 15px;
        }
        .btn-blue {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 25px;
            cursor: pointer;
        }
        .message {
            color: green;
        }
    </style>
</head>
<body>
<div class="box">
    <h2>Initial Admin Setup</h2>
    <form method="post">
        <input type="text" name="id" placeholder="id" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Create Admin" class="btn-blue">
    </form>
    <p class="message"><?php echo $message; ?></p>
</div>
</body>
</html>
