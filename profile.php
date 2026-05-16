<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo "<script>window.location.assign('login.php')</script>";
    die();
}

$con = mysqli_connect('localhost', 'root', '', 'rymowatch');
$username = $_SESSION['username'];

// ── Fetch user details ──
$user_result = mysqli_query($con, "SELECT * FROM regi WHERE username='$username'");
$user        = mysqli_fetch_assoc($user_result);
$user_email  = trim($user['email']);

// ── Handle profile update FIRST ──
$update_msg = '';
$update_error = '';
if (isset($_POST['update_profile'])) {
    $new_email = trim(mysqli_real_escape_string($con, $_POST['new_email']));
    $new_pass  = trim($_POST['new_pass']);
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $update_error = "Please enter a valid email address.";
    } elseif (!empty($new_pass) && strlen($new_pass) < 6) {
        $update_error = "Password must be at least 6 characters long.";
    } else {
        if (!empty($new_pass)) {
            $safe_pass = mysqli_real_escape_string($con, $new_pass);
            mysqli_query($con, "UPDATE regi SET email='$new_email', password='$safe_pass' WHERE username='$username'");
        } else {
            mysqli_query($con, "UPDATE regi SET email='$new_email' WHERE username='$username'");
        }
        $update_msg = "Profile updated successfully!";
        $user_result = mysqli_query($con, "SELECT * FROM regi WHERE username='$username'");
        $user        = mysqli_fetch_assoc($user_result);
        $user_email  = trim($user['email']);
    }
}

// ── Fetch orders: TRIM both sides to handle spaces stored in DB ──
$esc_user  = mysqli_real_escape_string($con, $username);
$esc_email = mysqli_real_escape_string($con, $user_email);

$orders_result = mysqli_query($con,
    "SELECT * FROM book_form
     WHERE TRIM(name) = '$esc_user'
        OR TRIM(email) = '$esc_email'
     ORDER BY id DESC");
$total_orders = mysqli_num_rows($orders_result);

// ── Price map (DB stores 0.00 due to preg_replace bug in book_form.php) ──
// These are the REAL prices from buy.php
$price_map = [
    'Sport Watch'                       => 2230,
    'Formal Wear'                       => 3499,
    'Smart Watch'                       => 4500,
    'Curren'                            => 1500,
    'Perfomance Gear'                   => 4899,
    'Gentle Wear'                       => 3499,
    'Aura'                              => 2999,
    'Rolex'                             => 45000,
    'MTG-B3000'                         => 8990,
    'GA-2100SRS'                        => 7899,
    'GA-700BNR'                         => 14999,
    'Deepsea Challenge'                 => 69999,
    'Sea-Dweller'                       => 78999,
    'Air-King'                          => 50000,
    'night jewl'                        => 100000,
    'Centrix'                           => 44999,
    'High-Tech Ceramic Limited Edition' => 33999,
    'DiaStar Original'                  => 22399,
    'True Square'                       => 45000,
    'Tresor Quartz'                     => 18999,
    'Moonwatch Professional'            => 65000,
    'Diver 300M Sea-Master'             => 82000,
    'Citizen Eco-Drive Gents Watch'     => 16000,
    'Citizen Automatic Gents Watch'     => 19999,
    'Citizen Eco-Drive CA0617-11E'      => 16000,
    'MP-09 Tourbillon Bi-Axis Red'      => 320000,
    'Unico SORAI'                       => 18000,
    'Tourbillon Bi-Axis'                => 13999,
    'Titanium Ceramic'                  => 16999,
    'ID116 Plus'                        => 3500,
    'Gladiator'                         => 3000,
    'Super Nova'                        => 7900,
    'Ninja 3 Smartwatch'                => 3200,
    'Rado'                              => 28999,
    'TAG Heuer'                         => 49999,
    'Patek Philippe'                    => 25000,
];

// ── Calculate total spent ──
$total_spent   = 0;
$orders_for_total = mysqli_query($con,
    "SELECT wname, wprice FROM book_form
     WHERE TRIM(name)='$esc_user' OR TRIM(email)='$esc_email'");
while ($r = mysqli_fetch_assoc($orders_for_total)) {
    $stored = (float)$r['wprice'];
    $name   = trim($r['wname']);
    $total_spent += ($stored > 0) ? $stored : ($price_map[$name] ?? 0);
}

// ── Most ordered watch ──
$fav_res   = mysqli_query($con,
    "SELECT wname, COUNT(*) as cnt FROM book_form
     WHERE TRIM(name)='$esc_user' OR TRIM(email)='$esc_email'
     GROUP BY wname ORDER BY cnt DESC LIMIT 1");
$fav_watch = ($fav_res && mysqli_num_rows($fav_res) > 0)
             ? trim(mysqli_fetch_assoc($fav_res)['wname'])
             : 'N/A';

// ── Member tier ──
$tier = $total_orders >= 5 ? 'Gold' : ($total_orders >= 2 ? 'Silver' : 'Bronze');
$tier_color = $tier === 'Gold' ? '#e67e22' : ($tier === 'Silver' ? '#888' : '#cd7f32');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Profile – Rymo Watches</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link rel="stylesheet" href="style.css"/>
  <style>
    body { background:#f0f2ff; padding-top:80px; }

    .profile-hero { background:linear-gradient(135deg,#1d1d1d 0%,#6c42f8 100%); color:#fff; padding:50px 0 35px; text-align:center; }
    .avatar-wrap  { width:90px; height:90px; border-radius:50%; background:linear-gradient(135deg,#fda283,#e67e22); display:flex; align-items:center; justify-content:center; font-size:2.7rem; color:#fff; margin:0 auto 14px; box-shadow:0 6px 20px rgba(0,0,0,0.3); }
    .profile-hero h2 { font-weight:700; font-size:1.8rem; text-transform:capitalize; }
    .profile-hero p  { opacity:0.8; font-size:0.88rem; margin-top:4px; }

    .stats-strip { background:#fff; display:flex; flex-wrap:wrap; box-shadow:0 4px 18px rgba(0,0,0,0.09); margin-bottom:30px; }
    .stat-item   { flex:1; min-width:140px; padding:20px 10px; text-align:center; border-right:1px solid #f0f0f0; }
    .stat-item:last-child { border-right:none; }
    .stat-item .s-val { font-size:1.75rem; font-weight:800; background:linear-gradient(135deg,#6c42f8,#fda283); -webkit-background-clip:text; -webkit-text-fill-color:transparent; line-height:1; }
    .stat-item .s-lbl { font-size:0.78rem; color:#999; margin-top:5px; font-weight:500; }
    .stat-item .s-icon{ font-size:1.3rem; margin-bottom:7px; }

    .section-card { background:#fff; border-radius:14px; box-shadow:0 3px 16px rgba(0,0,0,0.08); padding:26px; margin-bottom:22px; }
    .card-title   { font-weight:700; color:#1d1d1d; font-size:1rem; border-bottom:2px solid #fda283; padding-bottom:10px; margin-bottom:18px; display:flex; align-items:center; gap:8px; }

    .form-control {
      border-radius:8px;
      border:1px solid #ddd;
      min-height:46px;
      padding:10px 14px;
      font-size:15px;
      line-height:1.45;
      vertical-align:middle;
      box-sizing:border-box;
    }
    select.form-control { padding-top:9px; padding-bottom:9px; }
    textarea.form-control { min-height:120px; padding-top:12px; padding-bottom:12px; }
    .form-control:focus { border-color:#6c42f8; box-shadow:0 0 0 0.15rem rgba(108,66,248,0.18); }
    .btn-save { width:100%; padding:12px; background:linear-gradient(135deg,#1d1d1d,#444); color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer; transition:0.3s; font-size:0.95rem; }
    .btn-save:hover { background:linear-gradient(135deg,#6c42f8,#9b59b6); }
    .alert-ok { background:#d4edda; color:#155724; border-radius:8px; padding:11px 15px; margin-bottom:14px; font-size:0.88rem; }

    .fav-card { background:linear-gradient(135deg,#1d1d1d,#3a3a3a); border-radius:12px; padding:18px 20px; color:#fff; margin-bottom:18px; }
    .fav-card .fav-label { font-size:0.75rem; color:#fda283; font-weight:700; letter-spacing:1px; text-transform:uppercase; }
    .fav-card .fav-name  { font-size:1.05rem; font-weight:700; margin-top:5px; }

    .orders-table thead th { background:#f4f0ff; color:#6c42f8; font-size:0.83rem; font-weight:700; border:none; padding:11px; }
    .orders-table tbody td { padding:12px 11px; font-size:0.88rem; vertical-align:middle; border-color:#f5f5f5; }
    .orders-table tbody tr:hover { background:#fafafa; }
    .badge-del   { background:#d4edda; color:#155724; padding:3px 12px; border-radius:20px; font-size:0.76rem; font-weight:700; }
    .watch-chip  { background:#f4f0ff; color:#6c42f8; border-radius:7px; padding:4px 10px; font-size:0.82rem; font-weight:600; }
    .price-chip  { color:#e67e22; font-weight:700; }

    .empty-orders { text-align:center; padding:35px; color:#bbb; }
    .empty-orders i { font-size:3rem; margin-bottom:10px; display:block; }

    .quick-btn { display:block; border-radius:12px; padding:18px; text-align:center; text-decoration:none; font-weight:700; color:#fff; transition:0.3s; }
    .quick-btn:hover { opacity:0.88; color:#fff; text-decoration:none; }
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
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link active" href="#"><i class="fa-solid fa-user"></i> Profile</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link"><i class="fa-solid fa-right-from-bracket"></i></a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="profile-hero">
  <div class="avatar-wrap"><i class="fa-solid fa-user"></i></div>
  <h2><?= htmlspecialchars($username) ?></h2>
  <p><i class="fa-solid fa-envelope fa-xs"></i> <?= htmlspecialchars($user_email) ?></p>
</div>

<!-- Live Stats Strip -->
<div class="stats-strip">
  <div class="stat-item">
    <div class="s-icon" style="color:#6c42f8"><i class="fa-solid fa-bag-shopping"></i></div>
    <div class="s-val"><?= $total_orders ?></div>
    <div class="s-lbl">Total Orders</div>
  </div>
  <div class="stat-item">
    <div class="s-icon" style="color:#e67e22"><i class="fa-solid fa-indian-rupee-sign"></i></div>
    <div class="s-val">₹<?= number_format($total_spent, 0) ?></div>
    <div class="s-lbl">Total Spent</div>
  </div>
  <div class="stat-item">
    <div class="s-icon" style="color:#28a745"><i class="fa-solid fa-circle-check"></i></div>
    <div class="s-val" style="font-size:1.2rem">Active</div>
    <div class="s-lbl">Account Status</div>
  </div>
  <div class="stat-item">
    <div class="s-icon" style="color:<?= $tier_color ?>"><i class="fa-solid fa-medal"></i></div>
    <div class="s-val" style="font-size:1.2rem;color:<?= $tier_color ?>;-webkit-text-fill-color:<?= $tier_color ?>"><?= $tier ?></div>
    <div class="s-lbl">Member Tier</div>
  </div>
</div>

<div class="container mb-5">
  <div class="row">

    <!-- LEFT: Fav Watch + Update Profile -->
    <div class="col-lg-4 mb-4">

      <?php if ($fav_watch !== 'N/A'): ?>
      <div class="fav-card">
        <div class="fav-label"><i class="fa-solid fa-watch"></i> Most Ordered Watch</div>
        <div class="fav-name"><?= htmlspecialchars($fav_watch) ?></div>
      </div>
      <?php endif; ?>

      <div class="section-card">
        <div class="card-title"><i class="fa-solid fa-pen-to-square" style="color:#fda283"></i> Update Profile</div>
        <?php if ($update_msg): ?>
          <div class="alert-ok"><i class="fa-solid fa-circle-check"></i> <?= $update_msg ?></div>
        <?php elseif ($update_error): ?>
          <div class="alert-ok" style="background:#ffe4e6;color:#8a2132;"><i class="fa-solid fa-circle-exclamation"></i> <?= $update_error ?></div>
        <?php endif; ?>
        <form action="profile.php" method="POST">
          <div class="form-group">
            <label style="font-size:0.85rem;font-weight:600;color:#555">Username</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($username) ?>" disabled style="background:#f8f8f8">
            <small class="text-muted" style="font-size:0.75rem">Cannot be changed</small>
          </div>
          <div class="form-group">
            <label style="font-size:0.85rem;font-weight:600;color:#555">Email Address</label>
            <input type="email" name="new_email" class="form-control" value="<?= htmlspecialchars($user_email) ?>" required>
          </div>
          <div class="form-group">
            <label style="font-size:0.85rem;font-weight:600;color:#555">New Password</label>
            <input type="password" name="new_pass" class="form-control" placeholder="Leave blank to keep current" minlength="6">
          </div>
          <button type="submit" name="update_profile" class="btn-save">
            <i class="fa-solid fa-floppy-disk"></i> Save Changes
          </button>
        </form>
      </div>
    </div>

    <!-- RIGHT: Order History -->
    <div class="col-lg-8">
      <div class="section-card">
        <div class="card-title">
          <i class="fa-solid fa-clock-rotate-left" style="color:#6c42f8"></i>
          My Order History
          <span style="margin-left:auto;background:#f4f0ff;color:#6c42f8;border-radius:20px;padding:3px 13px;font-size:0.8rem;font-weight:700">
            <?= $total_orders ?> Order<?= $total_orders != 1 ? 's' : '' ?>
          </span>
        </div>

        <?php
        // Fresh query for display
        $orders_display = mysqli_query($con,
            "SELECT * FROM book_form
             WHERE TRIM(name)='$esc_user' OR TRIM(email)='$esc_email'
             ORDER BY id DESC");
        ?>

        <?php if (mysqli_num_rows($orders_display) == 0): ?>
          <div class="empty-orders">
            <i class="fa-solid fa-bag-shopping"></i>
            <strong>No orders found yet.</strong>
            <p style="font-size:0.88rem;margin-top:6px">Go to <a href="shop.php">Shop</a> to place your first order!</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table orders-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Watch Name</th>
                  <th>Price</th>
                  <th>City</th>
                  <th>Address</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php $sr = 1; $running_total = 0;
                while ($ord = mysqli_fetch_assoc($orders_display)):
                  $wname = trim($ord['wname']);
                  // Use stored price if > 0, else resolve from price_map
                  $price = ((float)$ord['wprice'] > 0)
                           ? (float)$ord['wprice']
                           : ($price_map[$wname] ?? 0);
                  $running_total += $price;
                ?>
                <tr>
                  <td style="color:#aaa;font-size:0.8rem"><?= $sr++ ?></td>
                  <td><span class="watch-chip"><?= htmlspecialchars($wname) ?></span></td>
                  <td><span class="price-chip">₹<?= number_format($price, 0) ?></span></td>
                  <td style="font-size:0.84rem"><?= htmlspecialchars(trim($ord['city'])) ?></td>
                  <td style="font-size:0.78rem;color:#999;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="<?= htmlspecialchars(trim($ord['address'])) ?>">
                    <?= htmlspecialchars(trim($ord['address'])) ?>
                  </td>
                  <td><span class="badge-del">✓ Delivered</span></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
              <tfoot>
                <tr style="background:#fff8f0">
                  <td colspan="2" style="font-weight:700;font-size:0.9rem;padding:13px;color:#555">
                    <i class="fa-solid fa-calculator" style="color:#e67e22"></i> Grand Total
                  </td>
                  <td colspan="4" style="font-weight:800;font-size:1.15rem;color:#e67e22;padding:13px">
                    ₹<?= number_format($total_spent, 0) ?>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        <?php endif; ?>
      </div>

      <!-- Quick Actions -->
      <div class="row">
        <div class="col-6 mb-3">
          <a href="shop.php" class="quick-btn" style="background:linear-gradient(135deg,#6c42f8,#9b59b6)">
            <i class="fa-solid fa-bag-shopping fa-lg d-block mb-1"></i>
            Shop More Watches
          </a>
        </div>
        <div class="col-6 mb-3">
          <a href="contact.php" class="quick-btn" style="background:linear-gradient(135deg,#fda283,#e67e22)">
            <i class="fa-solid fa-headset fa-lg d-block mb-1"></i>
            Contact Support
          </a>
        </div>
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
      <h5 class="pb-2">Quick Links</h5>
      <ul class="list-unstyled">
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="profile.php">My Profile</a></li>
      </ul>
    </div>
    <div class="footer-one col-lg-3 col-md-6 col-12 mb-3">
      <h5 class="pb-2">Contact Us</h5>
      <p>411001, Camp, Pune</p>
      <p>(+91)8857995764</p>
      <p>mohammedasifsawant93@gmail.com</p>
    </div>
    <div class="footer-one col-lg-3 col-md-6 col-12">
      <h5 class="pb-2">Social</h5>
      <a href="#"><i class="fa-brands fa-facebook fa-lg mr-3" style="color:#ccc"></i></a>
      <a href="#"><i class="fa-brands fa-instagram fa-lg mr-3" style="color:#ccc"></i></a>
      <a href="#"><i class="fa-brands fa-twitter fa-lg" style="color:#ccc"></i></a>
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
