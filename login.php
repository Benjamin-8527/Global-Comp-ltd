<?php
session_start();
include 'db_connect.php';

$message = "";

// Check if redirected from signup with success
$signupSuccess = isset($_GET['success']) && $_GET['success'] == 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashedPassword);
            $stmt->fetch();

            if ($password == $hashedPassword) { // You can improve security later with hashing
                $_SESSION['customer_logged_in'] = true;
                $_SESSION['customer_id'] = $id;
                $_SESSION['customer_name'] = $name;
                header("Location: customer_dashboard.php");
                exit();
            } else {
                $message = "❌ Incorrect password.";
            }
        } else {
            $message = "⚠️ Email not found.";
        }
        $stmt->close();
    } else {
        $message = "⚠️ All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Login - Global Computer Ltd</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            background-color: #374151;
            display: flex;
            justify-content: center;
            padding: 12px;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            margin: 0 6px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #2563eb;
        }
        .form-container {
            background: #fff;
            padding: 35px;
            max-width: 500px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .form-container h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 25px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error, .success {
            text-align: center;
            margin-top: -10px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .error {
            color: #dc3545;
        }
        .success {
            color: #28a745;
        }
        .back-btn {
            display: block;
            width: max-content;
            margin: 20px auto 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #1f2937;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
    <script>
        function validateForm() {
            const email = document.forms["loginForm"]["email"].value.trim();
            const password = document.forms["loginForm"]["password"].value.trim();

            if (!email || !password) {
                alert("⚠️ Please fill out all fields.");
                return false;
            }

            const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
            if (!email.match(emailPattern)) {
                alert("⚠️ Please enter a valid email address.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

<header>
    <h1>Global Computer Ltd - Customer Login</h1>
</header>

<nav>
    <a href="home page.html">🏠 Home</a>
    <a href="sign up.php">📝 Sign Up</a>
</nav>

<div class="form-container">
    <h2>Login</h2>
    <?php if ($signupSuccess): ?>
        <p class="success">✅ Signup successful! Please login below.</p>
    <?php endif; ?>
    <form name="loginForm" method="post" onsubmit="return validateForm()">
        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <?php if ($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <button type="submit">Login</button>
    </form>
</div>

<a href="home page.html" class="back-btn">⬅ Back to Home</a>

<footer>
    &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
</footer>

</body>
</html>
