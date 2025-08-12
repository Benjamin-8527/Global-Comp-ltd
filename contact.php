<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - GLOBAL COMPUTERS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
    }

    .sidebar {
      width: 250px;
      background-color: rgba(0, 0, 0, 0.9);
      color: white;
      padding-top: 20px;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #4CAF50;
    }

    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      transition: background 0.3s;
    }

    .sidebar a i {
      margin-right: 8px;
    }

    .sidebar a:hover {
      background-color: #4CAF50;
    }

    .main-content {
      margin-left: 250px;
      flex: 1;
      padding: 30px;
    }

    .container {
      background: white;
      max-width: 700px;
      margin: auto;
      padding: 30px;
      box-shadow: 0 0 12px rgba(0,0,0,0.15);
      border-radius: 12px;
    }

    h2 {
      text-align: center;
      color: #007bff;
      margin-bottom: 25px;
    }

    .info p {
      margin: 10px 0;
      font-size: 16px;
    }

    .info i {
      margin-right: 10px;
      color: #007bff;
    }

    input, textarea {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }

    input[type="submit"] {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #0056b3;
    }

    .success-message {
      color: green;
      text-align: center;
      margin-top: 15px;
    }

    footer {
      text-align: center;
      margin-top: 40px;
      font-size: 14px;
      color: #888;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2><i class="fas fa-globe"></i> GLOBAL</h2>
  <a href="index.html"><i class="fas fa-home"></i> Home</a>
  <a href="about us.php"><i class="fas fa-users"></i> About Us</a>
  <a href="Services.php"><i class="fas fa-cogs"></i> Services</a>
  <a href="Testimonials.php"><i class="fas fa-comment-dots"></i> Testimonials</a>
  <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
  <a href="sign up.php"><i class="fas fa-user-plus"></i> Register</a>
  <a href="admin login.php"><i class="fas fa-user-shield"></i> Admin</a>
  <a href="terms.php"><i class="fas fa-file-contract"></i> Terms</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="container">
    <h2><i class="fas fa-envelope"></i> Contact Us</h2>

    <div class="info">
      <p><i class="fas fa-phone"></i><strong>Phone:</strong> +254 748 765075</p>
      <p><i class="fas fa-envelope"></i><strong>Email:</strong> info@computerstore.com</p>
      <p><i class="fas fa-map-marker-alt"></i><strong>Address:</strong> Kimathi Street, Nairobi, Kenya</p>
    </div>

    <form method="post">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" placeholder="Your Message..." rows="5" required></textarea>
      <input type="submit" value="Send Message">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db_connect.php';
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $message = trim($_POST["message"]);

        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            echo "<p class='success-message'>Message sent successfully!</p>";
        }
        $stmt->close();
        $conn->close();
    }
    ?>
    
    <footer>&copy; 2025 GLOBAL COMPUTERS Ltd. | All rights reserved.</footer>
  </div>
</div>

</body>
</html>

