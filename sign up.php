<?php
session_start();
include 'db_connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $gender   = trim($_POST['gender']);

    if (!empty($name) && !empty($email) && !empty($password) && !empty($gender)) {
        // Check if email exists
        $check = $conn->prepare("SELECT id FROM customers WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "⚠️ Email already exists. Please choose another.";
        } else {
            $stmt = $conn->prepare("INSERT INTO customers (name, email, password, gender) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password, $gender);
            if ($stmt->execute()) {
                header("Location: login.php?success=1");
                exit();
            } else {
                $message = "❌ Something went wrong. Please try again.";
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $message = "⚠️ All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Signup - Global Computer Ltd</title>
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
        input, select {
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
        .error {
            color: #dc3545;
            margin-top: -15px;
            margin-bottom: 15px;
            font-size: 14px;
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
            const name = document.forms["signupForm"]["name"].value.trim();
            const email = document.forms["signupForm"]["email"].value.trim();
            const password = document.forms["signupForm"]["password"].value.trim();
            const gender = document.forms["signupForm"]["gender"].value;

            if (!name || !email || !password || !gender) {
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
    <h1>Global Computer Ltd - Customer Signup</h1>
</header>

<nav>
    <a href="home page.html">🏠 Home</a>
    <a href="login.php">🔑 Login</a>
</nav>

<div class="form-container">
    <h2>Create Account</h2>
    <form name="signupForm" method="post" onsubmit="return validateForm()">
        <input type="text" name="name" placeholder="Full Name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>

        <input type="email" name="email" placeholder="Email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
        <?php if ($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <input type="password" name="password" placeholder="Password" required>

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="male" <?= (isset($gender) && $gender == "male") ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= (isset($gender) && $gender == "female") ? 'selected' : '' ?>>Female</option>
        </select>

        <button type="submit">Sign Up</button>
    </form>
</div>

<footer>
    &copy; <?= date("Y") ?> Global Computer Ltd. All Rights Reserved.
</footer>

</body>
</html>
