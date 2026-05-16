<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo '<script>window.location.assign("login.php")</script>';
    die();
}

$con = mysqli_connect('localhost', 'root', '', 'rymowatch');
$messages_res = mysqli_query($con, "SELECT * FROM contact_us ORDER BY id DESC");
$total_msg    = mysqli_num_rows($messages_res);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Messages – Rymo Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
    body { background:#f0f2ff; min-height:100vh; }

    .sidebar { position:fixed; top:0; left:0; width:230px; height:100vh; background:linear-gradient(180deg,#1d1d1d,#2d2d2d); padding-top:30px; z-index:100; }
    .sidebar .logo { text-align:center; color:#fda283; font-size:1.4rem; font-weight:700; padding:0 20px 30px; border-bottom:1px solid #444; }
    .sidebar a { display:flex; align-items:center; gap:12px; padding:14px 25px; color:#ccc; text-decoration:none; transition:0.2s; font-size:0.95rem; }
    .sidebar a:hover, .sidebar a.active { background:rgba(253,162,131,0.15); color:#fda283; border-left:3px solid #fda283; }
    .sidebar a i { width:18px; }

    .main-content { margin-left:230px; padding:30px; }
    .page-header { background:linear-gradient(135deg,#1d1d1d,#6c42f8); color:#fff; border-radius:12px; padding:30px; margin-bottom:30px; display:flex; align-items:center; justify-content:space-between; }
    .page-header h2 { font-weight:700; margin:0; }
    .badge-count { background:#fda283; color:#fff; border-radius:20px; padding:6px 18px; font-weight:700; font-size:1rem; }

    .msg-card { background:#fff; border-radius:12px; padding:25px; box-shadow:0 3px 15px rgba(0,0,0,0.07); margin-bottom:18px; border-left:4px solid #6c42f8; transition:0.2s; }
    .msg-card:hover { box-shadow:0 6px 25px rgba(0,0,0,0.13); transform:translateY(-2px); }
    .msg-card .meta { display:flex; flex-wrap:wrap; gap:15px; margin-bottom:12px; }
    .msg-card .meta span { font-size:0.85rem; color:#666; }
    .msg-card .meta span i { margin-right:5px; color:#6c42f8; }
    .msg-card .subject-badge { background:#f0ecff; color:#6c42f8; border-radius:20px; padding:3px 12px; font-size:0.8rem; font-weight:600; display:inline-block; margin-bottom:10px; }
    .msg-card .message-text { background:#f8f9fa; border-radius:8px; padding:15px; color:#333; font-size:0.92rem; line-height:1.6; }
    .msg-card .msg-id { color:#bbb; font-size:0.78rem; float:right; }

    .empty-state { text-align:center; padding:60px; color:#999; }
    .empty-state i { font-size:4rem; margin-bottom:15px; color:#ddd; }

    @media(max-width:900px) {
      .sidebar { display:none; }
      .main-content { margin-left:0; }
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="logo"><i class="fa-solid fa-watch"></i> Rymo Admin</div>
  <a href="admin.php"><i class="fa-solid fa-table-list"></i> Customer Orders</a>
  <a href="view_users.php"><i class="fa-solid fa-users"></i> View Users</a>
  <a href="management_hub.php"><i class="fa-solid fa-grid-2"></i> Management Hub</a>
  <a href="advanced_report.php"><i class="fa-solid fa-chart-bar"></i> Reports</a>
  <a href="admin_contact.php" class="active"><i class="fa-solid fa-envelope"></i> Contact Messages</a>
  <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="main-content">
  <div class="page-header">
    <div>
      <h2><i class="fa-solid fa-inbox"></i> Contact Messages</h2>
      <p style="margin:5px 0 0;opacity:0.85;font-size:0.92rem;">All enquiries submitted by website visitors</p>
    </div>
    <div class="badge-count"><?= $total_msg ?> Messages</div>
  </div>

  <?php if ($total_msg == 0): ?>
    <div class="empty-state">
      <i class="fa-solid fa-envelope-open"></i>
      <h5>No messages yet</h5>
      <p>When visitors fill the Contact form, messages will appear here.</p>
    </div>
  <?php else: ?>
    <?php while ($msg = mysqli_fetch_assoc($messages_res)): ?>
      <div class="msg-card">
        <span class="msg-id">#<?= $msg['id'] ?></span>
        <span class="subject-badge"><i class="fa-solid fa-tag"></i> <?= htmlspecialchars($msg['subject']) ?></span>
        <div class="meta">
          <span><i class="fa-solid fa-user"></i> <?= htmlspecialchars($msg['name']) ?></span>
          <span><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($msg['email']) ?></span>
          <?php if (!empty($msg['phone'])): ?>
          <span><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($msg['phone']) ?></span>
          <?php endif; ?>
        </div>
        <div class="message-text"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
