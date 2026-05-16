<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo '<script>window.location.assign("login.php")</script>';
    die();
}

$con = mysqli_connect('localhost', 'root', '', 'rymowatch');
if (!$con) {
    die('Database connection failed.');
}

$feedback_res = mysqli_query($con, "SELECT * FROM feedback ORDER BY id DESC");
$total_feedback = $feedback_res ? mysqli_num_rows($feedback_res) : 0;
$avg_row = mysqli_fetch_assoc(mysqli_query($con, "SELECT COALESCE(AVG(rating), 0) AS avg_rating FROM feedback"));
$avg_rating = round((float) ($avg_row['avg_rating'] ?? 0), 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background: #eef1ff; min-height: 100vh; }
    .sidebar { position: fixed; top: 0; left: 0; width: 230px; height: 100vh; background: linear-gradient(180deg, #1d1d1d, #2d2d2d); padding-top: 30px; z-index: 100; }
    .sidebar .logo { text-align: center; color: #fda283; font-size: 1.4rem; font-weight: 700; padding: 0 20px 30px; border-bottom: 1px solid #444; }
    .sidebar a { display: flex; align-items: center; gap: 12px; padding: 14px 25px; color: #ccc; text-decoration: none; transition: 0.2s; font-size: 0.95rem; }
    .sidebar a:hover, .sidebar a.active { background: rgba(253,162,131,0.15); color: #fda283; border-left: 3px solid #fda283; }
    .main { margin-left: 230px; padding: 30px; }
    .header { background: linear-gradient(135deg, #1d1d1d, #6c42f8); color: #fff; border-radius: 16px; padding: 28px; margin-bottom: 22px; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .cards { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; margin-bottom: 22px; }
    .cardx { background: #fff; border-radius: 14px; padding: 22px; box-shadow: 0 8px 24px rgba(0,0,0,0.07); }
    .cardx .val { font-size: 1.8rem; font-weight: 700; }
    .list-card { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.07); }
    .feed-item { border-left: 4px solid #6c42f8; background: #fafbff; border-radius: 12px; padding: 18px; margin-bottom: 16px; }
    .feed-meta { display: flex; flex-wrap: wrap; gap: 14px; color: #666; font-size: 0.88rem; margin-bottom: 10px; }
    .stars { color: #f39c12; font-weight: 700; }
    @media (max-width: 900px) {
      .sidebar { display: none; }
      .main { margin-left: 0; }
      .cards { grid-template-columns: 1fr; }
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
  <a href="admin_contact.php"><i class="fa-solid fa-envelope"></i> Contact Messages</a>
  <a href="admin_feedback.php" class="active"><i class="fa-solid fa-star"></i> Feedback</a>
  <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="main">
  <div class="header">
    <div>
      <h2><i class="fa-solid fa-comments"></i> Customer Feedback Analytics</h2>
      <p style="margin:6px 0 0;opacity:0.85;">Track ratings and improvement suggestions from end users.</p>
    </div>
  </div>

  <div class="cards">
    <div class="cardx">
      <div class="val"><?= $total_feedback ?></div>
      <div>Total Feedback Entries</div>
    </div>
    <div class="cardx">
      <div class="val"><?= number_format($avg_rating, 1) ?>/5</div>
      <div>Average Rating</div>
    </div>
  </div>

  <div class="list-card">
    <h4 style="font-weight:700;margin-bottom:18px;">Feedback List</h4>
    <?php if ($total_feedback === 0): ?>
      <p style="color:#777;">No feedback has been submitted yet.</p>
    <?php else: ?>
      <?php while ($item = mysqli_fetch_assoc($feedback_res)): ?>
        <div class="feed-item">
          <div class="feed-meta">
            <span><i class="fa-solid fa-user"></i> <?= htmlspecialchars($item['name']) ?></span>
            <span><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($item['email']) ?></span>
            <span><i class="fa-solid fa-layer-group"></i> <?= htmlspecialchars($item['module_name']) ?></span>
            <span class="stars"><i class="fa-solid fa-star"></i> <?= (int) $item['rating'] ?>/5</span>
          </div>
          <p style="margin-bottom:8px;"><strong>Feedback:</strong> <?= nl2br(htmlspecialchars($item['message'])) ?></p>
          <p style="margin:0;"><strong>Improvement Suggestion:</strong> <?= nl2br(htmlspecialchars($item['improvement'])) ?></p>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
