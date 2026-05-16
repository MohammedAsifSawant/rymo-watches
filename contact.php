<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo "<script>window.location.assign('login.php')</script>";
    die();
}

$con = mysqli_connect('localhost', 'root', '', 'rymowatch');
$success = '';
$errors = [];
if (isset($_POST['send_contact'])) {
    $cname = trim($_POST['cname'] ?? '');
    $cemail = trim($_POST['cemail'] ?? '');
    $cphone = trim($_POST['cphone'] ?? '');
    $csubject = trim($_POST['csubject'] ?? '');
    $cmessage = trim($_POST['cmessage'] ?? '');

    if ($cname === '' || $cemail === '' || $csubject === '' || $cmessage === '') {
        $errors[] = 'Please fill all required fields.';
    }
    if (!filter_var($cemail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }
    if ($cphone !== '' && !preg_match('/^[0-9]{10}$/', $cphone)) {
        $errors[] = 'Phone number must contain exactly 10 digits.';
    }
    if (strlen($cmessage) < 10) {
        $errors[] = 'Message should be at least 10 characters long.';
    }

    if (empty($errors)) {
        $cname = mysqli_real_escape_string($con, $cname);
        $cemail = mysqli_real_escape_string($con, $cemail);
        $cphone = mysqli_real_escape_string($con, $cphone);
        $csubject = mysqli_real_escape_string($con, $csubject);
        $cmessage = mysqli_real_escape_string($con, $cmessage);
        $sql = "INSERT INTO contact_us (name, email, phone, subject, message) VALUES ('$cname','$cemail','$cphone','$csubject','$cmessage')";
        mysqli_query($con, $sql);
        $success = "Thank you! Your message has been sent successfully.";
        $_POST = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us – Rymo Watches</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link rel="stylesheet" href="style.css"/>
  <style>
    body { background: #f4f4f4; padding-top: 80px; }
    .contact-hero {
      background: linear-gradient(135deg, #1d1d1d 60%, #6c42f8 100%);
      color: #fff; padding: 60px 0 40px; text-align: center; margin-bottom: 40px;
    }
    .contact-hero h1 { font-size: 2.5rem; font-weight: 700; }
    .contact-card {
      background: #fff; border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.10); padding: 40px;
    }
    .contact-info-box {
      background: linear-gradient(135deg, #1d1d1d, #3a3a3a);
      color: #fff; border-radius: 12px; padding: 35px; height: 100%;
    }
    .contact-info-box h4 { color: #fda283; margin-bottom: 25px; }
    .info-item { display: flex; align-items: flex-start; margin-bottom: 20px; }
    .info-item i { font-size: 1.3rem; color: #fda283; margin-right: 15px; margin-top: 3px; }
    .form-control {
      border-radius: 8px;
      border: 1px solid #ddd;
      min-height: 46px;
      padding: 10px 14px;
      font-size: 15px;
      line-height: 1.45;
      vertical-align: middle;
      box-sizing: border-box;
    }
    select.form-control { padding-top: 9px; padding-bottom: 9px; }
    textarea.form-control { min-height: 120px; padding-top: 12px; padding-bottom: 12px; }
    .form-control:focus { border-color: #6c42f8; box-shadow: 0 0 0 0.2rem rgba(108,66,248,0.15); }
    .btn-submit { background: #1d1d1d; color: #fff; padding: 12px 40px; border-radius: 8px; font-weight: 600; border: none; transition: 0.3s; }
    .btn-submit:hover { background: #fda283; color: #fff; }
    .alert-success-custom { background: #d4edda; color: #155724; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light py-3 fixed-top">
  <div class="container">
    <img src="images/logo1.png" alt="logo">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="bar"><i class="navbar-toggler-icon"></i></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="website_products.php">Catalog</a></li>
        <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Contact</a></li>
        <li class="nav-item"><a href="logout.php"><i class="fa-solid fa-right-from-bracket mt-1"></i></a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="contact-hero">
  <h1><i class="fa-solid fa-envelope-open-text"></i> Contact Us</h1>
  <p class="lead">We'd love to hear from you. Send us a message!</p>
</div>

<div class="container mb-5">
  <?php if ($success): ?>
    <div class="alert-success-custom"><i class="fa-solid fa-circle-check"></i> <?= $success ?></div>
  <?php endif; ?>
  <?php if (!empty($errors)): ?>
    <div class="alert-success-custom" style="background:#ffe4e6;color:#8a2132;">
      <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars(implode(' ', $errors)) ?>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-4 mb-4">
      <div class="contact-info-box">
        <h4><i class="fa-solid fa-headset"></i> Get In Touch</h4>
        <div class="info-item">
          <i class="fa-solid fa-location-dot"></i>
          <div><strong>Address</strong><br>411001, Camp, Pune, Maharashtra</div>
        </div>
        <div class="info-item">
          <i class="fa-solid fa-phone"></i>
          <div><strong>Phone</strong><br>(+91) 8857995764<br>(+91) 7385237820</div>
        </div>
        <div class="info-item">
          <i class="fa-solid fa-envelope"></i>
          <div><strong>Email</strong><br>mohammedasifsawant93@gmail.com<br>abdulrahimombilkar03@gmail.com</div>
        </div>
        <div class="info-item">
          <i class="fa-solid fa-clock"></i>
          <div><strong>Working Hours</strong><br>Mon – Sat: 10am – 7pm<br>Sunday: Closed</div>
        </div>
        <hr style="border-color:#fda283">
        <div class="mt-3">
          <a href="#" class="text-warning mr-3 h5"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" class="text-warning mr-3 h5"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="text-warning h5"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="contact-card">
        <h4 class="mb-4" style="color:#1d1d1d;font-weight:700;">Send a Message</h4>
        <form action="contact.php" method="POST">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Full Name <span class="text-danger">*</span></label>
              <input type="text" name="cname" class="form-control" placeholder="Your full name" value="<?= htmlspecialchars($_POST['cname'] ?? '') ?>" minlength="3" maxlength="60" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Email Address <span class="text-danger">*</span></label>
              <input type="email" name="cemail" class="form-control" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['cemail'] ?? '') ?>" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Phone Number</label>
              <input type="text" name="cphone" class="form-control" placeholder="+91 XXXXXXXXXX" value="<?= htmlspecialchars($_POST['cphone'] ?? '') ?>" pattern="[0-9]{10}">
            </div>
            <div class="col-md-6 form-group">
              <label>Subject <span class="text-danger">*</span></label>
              <select name="csubject" class="form-control" required>
                <option value="">-- Select Subject --</option>
                <option>Order Enquiry</option>
                <option>Product Information</option>
                <option>Return / Refund</option>
                <option>Complaint</option>
                <option>Other</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Message <span class="text-danger">*</span></label>
            <textarea name="cmessage" class="form-control" rows="5" placeholder="Write your message here..." minlength="10" required><?= htmlspecialchars($_POST['cmessage'] ?? '') ?></textarea>
          </div>
          <button type="submit" name="send_contact" class="btn-submit">
            <i class="fa-solid fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<footer class="mt-5 py-5">
  <div class="row container mx-auto pt-5">
    <div class="footer-one col-lg-3 col-md-6 col-12">
      <img src="images/fotter/logo2.png" alt="">
      <p class="pt-3">Shop For The Best Products. With Rymo.</p>
    </div>
    <div class="footer-one col-lg-3 col-md-6 col-12 mb-3">
      <h5 class="pb-2">Featured</h5>
      <ul class="text-uppercase list-unstyled">
        <li><a href="#">men</a></li><li><a href="#">women</a></li>
        <li><a href="#">new arrivals</a></li><li><a href="#">watches</a></li>
      </ul>
    </div>
    <div class="footer-one col-lg-3 col-md-6 col-12 mb-3">
      <h5 class="pb-2">Contact Us</h5>
      <p>411001, Camp, Pune</p>
      <p>(+91)8857995764</p>
      <p>mohammedasifsawant93@gmail.com</p>
    </div>
    <div class="footer-one col-lg-3 col-md-6 col-12">
      <h5 class="pb-2">Quick Links</h5>
      <ul class="list-unstyled">
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="blog.php">Blog</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
  <div class="copyright mt-5">
    <div class="row container mx-auto">
      <div class="col-lg-3 col-md-6 col-12 mb-4"><img src="images/payment.png" alt=""></div>
      <div class="col-lg-3 col-md-6 col-12 text-nowrap mb-2"><p>rymo eComm. © 2026. All Rights Reserved.</p></div>
      <div class="col-lg-3 col-md-6 col-12">
        <a href="#"><i class="fa-brands fa-facebook"></i></a>
        <a href="#"><i class="fa-brands fa-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
