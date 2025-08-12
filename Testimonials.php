<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Testimonials - GLOBAL COMPUTERS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #eef2f3;
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
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
      overflow-y: auto;
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

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #4CAF50;
    }

    .main-content {
      margin-left: 250px;
      flex: 1;
      padding: 40px 20px;
    }

    h2 {
      text-align: center;
      color: #007bff;
      margin-bottom: 40px;
    }

    .testimonial {
      max-width: 700px;
      margin: 20px auto;
      background: white;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      position: relative;
    }

    .testimonial::before {
      content: "\f10d";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      font-style: normal;
      font-size: 24px;
      color: #007bff;
      position: absolute;
      top: 20px;
      left: 20px;
    }

    .testimonial p {
      font-style: italic;
      font-size: 16px;
      margin: 20px 0;
      color: #444;
    }

    .author {
      text-align: right;
      font-weight: bold;
      color: #333;
    }

    .author i {
      margin-right: 5px;
      color: #007bff;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
      }

      .main-content {
        margin-left: 0;
        padding-top: 20px;
      }
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
  <a href="Testimonials.php" class="active"><i class="fas fa-comment-dots"></i> Testimonials</a>
  <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
  <a href="sign up.php"><i class="fas fa-user-plus"></i> Register</a>
  <a href="admin login.php"><i class="fas fa-user-shield"></i> Admin</a>
  <a href="terms.php"><i class="fas fa-file-contract"></i> Terms</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <h2><i class="fas fa-comments"></i> What Our Customers Say</h2>

  <div class="testimonial">
    <p>"I ordered a custom gaming PC and it exceeded all my expectations. Excellent service!"</p>
    <div class="author"><i class="fas fa-user"></i> Lenny M.</div>
  </div>

  <div class="testimonial">
    <p>"Affordable laptops and fast delivery. Highly recommended!"</p>
    <div class="author"><i class="fas fa-user"></i> Paul N.</div>
  </div>

  <div class="testimonial">
    <p>"Customer support is amazing. They helped me choose the perfect setup for my business."</p>
    <div class="author"><i class="fas fa-user"></i> Bruno T.</div>
  </div>
</div>

</body>
</html>

