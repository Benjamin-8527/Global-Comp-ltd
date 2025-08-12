<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Our Services - GLOBAL COMPUTERS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      display: flex;
      min-height: 100vh;
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
      padding: 40px 20px;
      flex: 1;
    }

    h2 {
      text-align: center;
      font-size: 32px;
      color: #333;
      margin-bottom: 40px;
    }

    .service-box {
      background: white;
      margin: 20px auto;
      padding: 25px 30px;
      max-width: 700px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
    }

    .service-box h3 {
      color: #007bff;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .service-box p {
      color: #555;
      font-size: 16px;
      line-height: 1.5;
    }

    .platform-icons {
      list-style: none;
      padding: 0;
      margin-top: 15px;
    }

    .platform-icons li {
      margin-bottom: 10px;
      font-size: 16px;
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .platform-icons i {
      width: 20px;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: relative;
        width: 100%;
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
  <a href="Services.php" class="active"><i class="fas fa-cogs"></i> Services</a>
  <a href="Testimonials.php"><i class="fas fa-comment-dots"></i> Testimonials</a>
  <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
  <a href="sign up.php"><i class="fas fa-user-plus"></i> Register</a>
  <a href="admin login.php"><i class="fas fa-user-shield"></i> Admin</a>
  <a href="terms.php"><i class="fas fa-file-contract"></i> Terms</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <h2><i class="fas fa-tools"></i> Our Services</h2>

  <div class="service-box">
    <h3><i class="fas fa-desktop"></i> 1. Computer Sales</h3>
    <p>We sell high-quality desktops, laptops, and custom PCs for all needs – gaming, office, school.</p>
  </div>

  <div class="service-box">
    <h3><i class="fas fa-bullhorn"></i> 2. Online Marketing</h3>
    <p>We advertise your products and services on online platforms such as:</p>
    <ul class="platform-icons">
      <li><i class="fab fa-facebook" style="color: #3b5998;"></i> Facebook</li>
      <li><i class="fab fa-whatsapp" style="color: #25D366;"></i> WhatsApp</li>
      <li><i class="fab fa-twitter" style="color: #1DA1F2;"></i> Twitter</li>
      <li><i class="fab fa-instagram" style="color: #C13584;"></i> Instagram</li>
    </ul>
  </div>

  <div class="service-box">
    <h3><i class="fas fa-wrench"></i> 3. Custom Builds</h3>
    <p>We help you build custom machines tailored to your specs – performance, budget, and purpose.</p>
  </div>

  <div class="service-box">
    <h3><i class="fas fa-tools"></i> 4. Repairs & Upgrades</h3>
    <p>We offer diagnostic, repair, and hardware upgrade services for all major PC brands.</p>
  </div>

  <div class="service-box">
    <h3><i class="fas fa-headset"></i> 5. IT Consultation</h3>
    <p>For businesses or schools, we offer personalized tech advice, procurement planning, and setup support.</p>
  </div>
</div>

</body>
</html>

