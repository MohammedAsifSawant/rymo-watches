<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo "<script>window.location.assign('login.php')</script>";
    die();
}

$con = mysqli_connect('localhost', 'root', '', 'rymowatch');
if (!$con) {
    die('Database connection failed.');
}

$success = '';
$username = $_SESSION['username'];
$user_query = mysqli_query($con, "SELECT * FROM regi WHERE username='" . mysqli_real_escape_string($con, $username) . "'");
$user = mysqli_fetch_assoc($user_query);
$default_email = $user['email'] ?? '';

if (isset($_POST['submit_feedback'])) {
    $name = mysqli_real_escape_string($con, trim($_POST['fname'] ?? ''));
    $email = mysqli_real_escape_string($con, trim($_POST['femail'] ?? ''));
    $rating = (int) ($_POST['rating'] ?? 0);
    $module_name = mysqli_real_escape_string($con, trim($_POST['module_name'] ?? ''));
    $message = mysqli_real_escape_string($con, trim($_POST['message'] ?? ''));
    $improvement = mysqli_real_escape_string($con, trim($_POST['improvement'] ?? ''));

    if ($name !== '' && $email !== '' && $rating >= 1 && $rating <= 5 && $module_name !== '' && $message !== '') {
        mysqli_query(
            $con,
            "INSERT INTO feedback (name, email, rating, module_name, message, improvement) VALUES ('$name', '$email', $rating, '$module_name', '$message', '$improvement')"
        );
        $success = 'Feedback submitted successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Feedback - Rymo Watches</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body { background: #f3f5ff; padding-top: 80px; }
    .hero { background: linear-gradient(135deg, #1d1d1d, #6c42f8); color: #fff; padding: 55px 0 38px; text-align: center; margin-bottom: 35px; }
    .hero h1 { font-weight: 700; }
    .feedback-card { background: #fff; border-radius: 16px; box-shadow: 0 8px 26px rgba(0,0,0,0.08); padding: 32px; }
    .form-control, .custom-select {
      border-radius: 10px;
      min-height: 46px;
      padding: 10px 14px;
      border: 1px solid #d7dbf1;
      font-size: 15px;
      line-height: 1.45;
      vertical-align: middle;
      box-sizing: border-box;
    }
    select.form-control, .custom-select {
      padding-top: 9px;
      padding-bottom: 9px;
    }
    textarea.form-control {
      min-height: 120px;
      padding-top: 12px;
      padding-bottom: 12px;
    }
    .btn-submit { background: #6c42f8; color: #fff; border: none; border-radius: 10px; padding: 12px 22px; font-weight: 700; }
    .info-card { background: linear-gradient(135deg, #fff1ec, #fef7f4); border-radius: 16px; padding: 26px; height: 100%; }
    .info-card li { margin-bottom: 12px; color: #555; }
    .ok { background: #d4edda; color: #155724; border-radius: 10px; padding: 12px 14px; margin-bottom: 18px; }
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
        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
        <li class="nav-item"><a class="nav-link active" href="feedback.php">Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a href="logout.php"><i class="fa-solid fa-right-from-bracket mt-1"></i></a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="hero">
  <h1><i class="fa-solid fa-star"></i> Customer Feedback Form</h1>
  <p class="lead mb-0">An additional input screen for collecting user opinions and suggestions.</p>
</div>

<div class="container mb-5">
  <div class="row">
    <div class="col-lg-8 mb-4">
      <div class="feedback-card">
        <?php if ($success !== ''): ?>
          <div class="ok"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <h4 style="font-weight:700;margin-bottom:18px;">Share Your Experience</h4>
        <form method="POST" action="feedback.php">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Full Name</label>
              <input type="text" name="fname" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Email</label>
              <input type="email" name="femail" class="form-control" value="<?= htmlspecialchars($default_email) ?>" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Rating</label>
              <select name="rating" class="custom-select" required>
                <option value="">Select rating</option>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Very Good</option>
                <option value="3">3 - Good</option>
                <option value="2">2 - Average</option>
                <option value="1">1 - Poor</option>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Module Name</label>
              <select name="module_name" class="custom-select" required>
                <option value="">Select module</option>
                <option value="Order Management">Order Management</option>
                <option value="Reports">Reports</option>
                <option value="Profile">Profile</option>
                <option value="Contact">Contact</option>
                <option value="Shop">Shop</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Feedback Message</label>
            <textarea name="message" rows="5" class="form-control" placeholder="Write your feedback here..." required></textarea>
          </div>
          <div class="form-group">
            <label>Suggested Improvement</label>
            <textarea name="improvement" rows="3" class="form-control" placeholder="What should be improved in this system?"></textarea>
          </div>
          <button type="submit" name="submit_feedback" class="btn-submit"><i class="fa-solid fa-paper-plane"></i> Submit Feedback</button>
        </form>
      </div>
    </div>
    <div class="col-lg-4">
  <div class="info-card">
    <h4 style="font-weight:700;margin-bottom:14px;">Customer Support</h4>
    <p class="text-muted small">Your feedback helps us provide a better luxury experience. Our team reviews every submission within 24 hours.</p>
    <ul class="list-unstyled">
      <li><i class="fa-solid fa-clock text-primary"></i> <strong>Response Time:</strong> 24/7 Support</li>
      <li><i class="fa-solid fa-envelope text-primary"></i> <strong>Email:</strong> support@rymo.com</li>
      <li><i class="fa-solid fa-phone text-primary"></i> <strong>Helpline:</strong> +91 98765 43210</li>
      <li><i class="fa-solid fa-location-dot text-primary"></i> <strong>HQ:</strong> Pune, Maharashtra</li>
    </ul>
    <hr>
    <p class="small">Looking for order tracking? Visit your <a href="profile.php">Profile</a> page.</p>
  </div>
</div>
    </div>
  </div>
</div>
</body>
</html>
