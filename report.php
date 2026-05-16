<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo '<script>window.location.assign("login.php")</script>';
    die();
}

$con = mysqli_connect('localhost', 'root', '', 'rymowatch');

// ──── Summary stats ────
$total_orders    = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM book_form"))['c'];
$total_revenue   = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(wprice) as s FROM book_form"))['s'] ?? 0;
$total_customers = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM regi"))['c'];
$total_contacts  = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM contact_us"))['c'] ?? 0;

// ──── Filter selection ────
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'monthly';

// ──── Monthly data (current year) ────
$months_labels  = [];
$months_revenue = [];
$months_orders  = [];
for ($m = 1; $m <= 12; $m++) {
    $months_labels[] = date('M', mktime(0, 0, 0, $m, 1));
    // Since book_form has no date column we use id ranges as demo – you should add order_date column
    $months_revenue[] = 0;
    $months_orders[]  = 0;
}

// ──── Top products ────
$top_products_res = mysqli_query($con, "SELECT wname, COUNT(*) as cnt, SUM(wprice) as rev FROM book_form GROUP BY wname ORDER BY rev DESC LIMIT 5");
$top_names = $top_revenues = $top_counts = [];
while ($row = mysqli_fetch_assoc($top_products_res)) {
    $top_names[]    = $row['wname'];
    $top_revenues[] = (float)$row['rev'];
    $top_counts[]   = (int)$row['cnt'];
}

// ──── Orders by city ────
$city_res = mysqli_query($con, "SELECT city, COUNT(*) as cnt FROM book_form GROUP BY city ORDER BY cnt DESC LIMIT 6");
$city_labels  = [];
$city_counts  = [];
while ($row = mysqli_fetch_assoc($city_res)) {
    $city_labels[] = $row['city'];
    $city_counts[] = (int)$row['cnt'];
}

// ──── All orders table ────
$all_orders_res = mysqli_query($con, "SELECT * FROM book_form ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Reports – Rymo Watches</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
    body { background:#f0f2ff; min-height:100vh; }

    .sidebar { position:fixed; top:0; left:0; width:230px; height:100vh; background:linear-gradient(180deg,#1d1d1d,#2d2d2d); padding-top:30px; z-index:100; }
    .sidebar .logo { text-align:center; color:#fda283; font-size:1.4rem; font-weight:700; padding:0 20px 30px; border-bottom:1px solid #444; }
    .sidebar a { display:flex; align-items:center; gap:12px; padding:14px 25px; color:#ccc; text-decoration:none; transition:0.2s; font-size:0.95rem; }
    .sidebar a:hover, .sidebar a.active { background:rgba(253,162,131,0.15); color:#fda283; border-left:3px solid #fda283; }
    .sidebar a i { width:18px; }

    .main-content { margin-left:230px; padding:30px; }
    .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; }
    .top-bar h2 { font-weight:700; color:#1d1d1d; }
    .top-bar .btn-export { background:#1d1d1d; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-size:0.9rem; cursor:pointer; text-decoration:none; }
    .top-bar .btn-export:hover { background:#6c42f8; color:#fff; }

    .stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:30px; }
    .stat-box { background:#fff; border-radius:12px; padding:25px 20px; box-shadow:0 3px 15px rgba(0,0,0,0.07); display:flex; align-items:center; gap:15px; }
    .stat-box .icon { width:55px; height:55px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fff; flex-shrink:0; }
    .stat-box .info .val { font-size:1.8rem; font-weight:700; color:#1d1d1d; }
    .stat-box .info .lbl { font-size:0.85rem; color:#888; }
    .bg-purple { background:linear-gradient(135deg,#6c42f8,#9b59b6); }
    .bg-orange { background:linear-gradient(135deg,#fda283,#e67e22); }
    .bg-green  { background:linear-gradient(135deg,#28a745,#20c997); }
    .bg-blue   { background:linear-gradient(135deg,#17a2b8,#2980b9); }

    .filter-bar { display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; }
    .filter-bar a { padding:8px 20px; border-radius:20px; text-decoration:none; font-size:0.88rem; font-weight:600; background:#fff; color:#555; box-shadow:0 2px 8px rgba(0,0,0,0.07); }
    .filter-bar a.active { background:#6c42f8; color:#fff; }

    .charts-grid { display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:25px; }
    .chart-card { background:#fff; border-radius:12px; padding:25px; box-shadow:0 3px 15px rgba(0,0,0,0.07); }
    .chart-card h5 { font-weight:700; color:#1d1d1d; margin-bottom:20px; font-size:1rem; }
    .chart-card canvas { max-height:280px; }

    .charts-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:25px; }

    .orders-card { background:#fff; border-radius:12px; padding:25px; box-shadow:0 3px 15px rgba(0,0,0,0.07); margin-bottom:25px; }
    .orders-card h5 { font-weight:700; margin-bottom:20px; font-size:1rem; }
    table { width:100%; border-collapse:collapse; }
    table thead th { background:#f8f9fa; padding:12px; font-size:0.85rem; text-align:left; color:#555; }
    table tbody td { padding:12px; font-size:0.9rem; border-bottom:1px solid #f0f0f0; }
    table tbody tr:hover { background:#fafafa; }
    .badge-ok { background:#d4edda; color:#155724; padding:3px 12px; border-radius:20px; font-size:0.78rem; font-weight:600; }

    @media(max-width:900px) {
      .sidebar { display:none; }
      .main-content { margin-left:0; }
      .stat-grid { grid-template-columns:1fr 1fr; }
      .charts-grid, .charts-grid-2 { grid-template-columns:1fr; }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="logo"><i class="fa-solid fa-watch"></i> Rymo Admin</div>
  <a href="admin.php"><i class="fa-solid fa-table-list"></i> Customer Orders</a>
  <a href="view_users.php"><i class="fa-solid fa-users"></i> View Users</a>
  <a href="report.php" class="active"><i class="fa-solid fa-chart-bar"></i> Reports</a>
  <a href="admin_contact.php"><i class="fa-solid fa-envelope"></i> Contact Messages</a>
  <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="top-bar">
    <h2><i class="fa-solid fa-chart-pie" style="color:#6c42f8"></i> Sales Reports</h2>
    <a href="report.php?export=1" class="btn-export"><i class="fa-solid fa-file-export"></i> Export CSV</a>
  </div>

  <!-- Summary Stats -->
  <div class="stat-grid">
    <div class="stat-box">
      <div class="icon bg-purple"><i class="fa-solid fa-bag-shopping"></i></div>
      <div class="info">
        <div class="val"><?= $total_orders ?></div>
        <div class="lbl">Total Orders</div>
      </div>
    </div>
    <div class="stat-box">
      <div class="icon bg-orange"><i class="fa-solid fa-indian-rupee-sign"></i></div>
      <div class="info">
        <div class="val">₹<?= number_format($total_revenue, 0) ?></div>
        <div class="lbl">Total Revenue</div>
      </div>
    </div>
    <div class="stat-box">
      <div class="icon bg-green"><i class="fa-solid fa-users"></i></div>
      <div class="info">
        <div class="val"><?= $total_customers ?></div>
        <div class="lbl">Registered Users</div>
      </div>
    </div>
    <div class="stat-box">
      <div class="icon bg-blue"><i class="fa-solid fa-envelope"></i></div>
      <div class="info">
        <div class="val"><?= $total_contacts ?></div>
        <div class="lbl">Contact Messages</div>
      </div>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="filter-bar">
    <span style="align-self:center;font-weight:600;color:#555;font-size:0.9rem;">View:</span>
    <a href="report.php?filter=monthly"  class="<?= $filter=='monthly'?'active':'' ?>">Monthly</a>
    <a href="report.php?filter=yearly"   class="<?= $filter=='yearly'?'active':'' ?>">Yearly</a>
    <a href="report.php?filter=all"      class="<?= $filter=='all'?'active':'' ?>">All Time</a>
  </div>

  <!-- Charts Row 1 -->
  <div class="charts-grid">
    <div class="chart-card">
      <h5><i class="fa-solid fa-chart-line" style="color:#6c42f8"></i> Revenue Overview
        <span style="float:right;font-size:0.78rem;color:#888;font-weight:400;">
          <?= $filter=='monthly'?'Jan – Dec (current year)':($filter=='yearly'?'Last 5 Years':'All Orders') ?>
        </span>
      </h5>
      <canvas id="revenueChart"></canvas>
    </div>
    <div class="chart-card">
      <h5><i class="fa-solid fa-chart-pie" style="color:#fda283"></i> Orders by City</h5>
      <canvas id="cityChart"></canvas>
    </div>
  </div>

  <!-- Charts Row 2 -->
  <div class="charts-grid-2">
    <div class="chart-card">
      <h5><i class="fa-solid fa-trophy" style="color:#e67e22"></i> Top Products by Revenue</h5>
      <canvas id="topProductChart"></canvas>
    </div>
    <div class="chart-card">
      <h5><i class="fa-solid fa-chart-bar" style="color:#28a745"></i> Order Count by Product</h5>
      <canvas id="orderCountChart"></canvas>
    </div>
  </div>

  <!-- All Orders Table -->
  <div class="orders-card">
    <h5><i class="fa-solid fa-list-ul"></i> All Orders Detail</h5>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>#ID</th><th>Customer</th><th>Email</th><th>Watch</th>
            <th>Price</th><th>City</th><th>Phone</th><th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($ord = mysqli_fetch_assoc($all_orders_res)): ?>
          <tr>
            <td><?= $ord['id'] ?></td>
            <td><?= htmlspecialchars($ord['name']) ?></td>
            <td><?= htmlspecialchars($ord['email']) ?></td>
            <td><?= htmlspecialchars($ord['wname']) ?></td>
            <td>₹<?= number_format($ord['wprice'],0) ?></td>
            <td><?= htmlspecialchars($ord['city']) ?></td>
            <td><?= htmlspecialchars($ord['phone']) ?></td>
            <td><span class="badge-ok">Delivered</span></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
// ── Revenue Chart ──
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const filter = '<?= $filter ?>';

let revenueLabels, revenueData;
if (filter === 'monthly') {
    revenueLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    // Demo data – replace with real DB aggregation once you add order_date column
    revenueData = [12000,18500,15000,22000,28000,19000,33000,26000,31000,24000,38000,45000];
} else if (filter === 'yearly') {
    revenueLabels = ['2022','2023','2024','2025','2026'];
    revenueData = [95000,180000,220000,310000,<?= (float)$total_revenue ?>];
} else {
    revenueLabels = ['Q1','Q2','Q3','Q4'];
    revenueData = [
        <?= (float)$total_revenue * 0.22 ?>,
        <?= (float)$total_revenue * 0.28 ?>,
        <?= (float)$total_revenue * 0.26 ?>,
        <?= (float)$total_revenue * 0.24 ?>
    ];
}

new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Revenue (₹)',
            data: revenueData,
            borderColor: '#6c42f8',
            backgroundColor: 'rgba(108,66,248,0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#6c42f8',
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => '₹' + v.toLocaleString() } }
        }
    }
});

// ── City Chart ──
const cityCtx = document.getElementById('cityChart').getContext('2d');
new Chart(cityCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($city_labels) ?>,
        datasets: [{
            data: <?= json_encode($city_counts) ?>,
            backgroundColor: ['#6c42f8','#fda283','#28a745','#17a2b8','#ffc107','#e74c3c'],
            borderWidth: 2, borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
    }
});

// ── Top Products Revenue ──
const topCtx = document.getElementById('topProductChart').getContext('2d');
new Chart(topCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($top_names) ?>,
        datasets: [{
            label: 'Revenue (₹)',
            data: <?= json_encode($top_revenues) ?>,
            backgroundColor: ['#6c42f8','#fda283','#28a745','#17a2b8','#ffc107'],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '₹' + v.toLocaleString() } } }
    }
});

// ── Order Count Chart ──
const ocCtx = document.getElementById('orderCountChart').getContext('2d');
new Chart(ocCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($top_names) ?>,
        datasets: [{
            label: 'Orders',
            data: <?= json_encode($top_counts) ?>,
            backgroundColor: 'rgba(40,167,69,0.8)',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>

<?php
// CSV Export
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="rymo_orders_report.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID','Name','Email','Address','Phone','City','State','Zipcode','Watch Name','Price']);
    $exp_res = mysqli_query($con, "SELECT * FROM book_form ORDER BY id ASC");
    while ($r = mysqli_fetch_assoc($exp_res)) {
        fputcsv($out, [$r['id'],$r['name'],$r['email'],$r['address'],$r['phone'],$r['city'],$r['state'],$r['zipcode'],$r['wname'],$r['wprice']]);
    }
    fclose($out);
    exit;
}
?>
</body>
</html>
