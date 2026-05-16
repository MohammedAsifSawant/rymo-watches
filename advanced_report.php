<?php
session_start();
require_once 'portal_helpers.php';
portal_require_admin();

$con = portal_db();

$filter = $_GET['filter'] ?? 'monthly';
$allowed_filters = ['monthly', 'yearly', 'all'];
if (!in_array($filter, $allowed_filters, true)) {
    $filter = 'monthly';
}

$year = (int) ($_GET['year'] ?? date('Y'));
if ($year < 2020 || $year > 2100) {
    $year = (int) date('Y');
}

$hasOrderDate = portal_column_exists('book_form', 'order_date');
$hasGrandTotal = portal_column_exists('book_form', 'grand_total');
$hasStatus = portal_column_exists('book_form', 'status');
$amountColumn = $hasGrandTotal ? 'grand_total' : 'wprice';
$dateColumn = $hasOrderDate ? 'order_date' : 'NOW()';
$statusColumn = $hasStatus ? 'status' : "'Delivered'";

$total_orders = portal_safe_count('book_form');
$total_revenue = portal_table_exists('book_form') ? (float) (mysqli_fetch_assoc(mysqli_query($con, "SELECT COALESCE(SUM($amountColumn), 0) AS s FROM book_form"))['s'] ?? 0) : 0;
$total_customers = portal_safe_count('regi');
$total_contacts = portal_safe_count('contact_us');
$delivered_orders = portal_table_exists('book_form') ? (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS c FROM book_form WHERE $statusColumn='Delivered'"))['c'] : 0;
$pending_orders = portal_table_exists('book_form') ? (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS c FROM book_form WHERE $statusColumn='Pending'"))['c'] : 0;

$monthly_revenue = array_fill(1, 12, 0.0);
$monthly_orders = array_fill(1, 12, 0);
$monthly_res = mysqli_query(
    $con,
    "SELECT MONTH($dateColumn) AS month_no, COUNT(*) AS total_orders, COALESCE(SUM($amountColumn), 0) AS total_revenue
     FROM book_form
     WHERE YEAR($dateColumn) = $year
     GROUP BY MONTH($dateColumn)"
);
while ($row = mysqli_fetch_assoc($monthly_res)) {
    $month_no = (int) $row['month_no'];
    $monthly_revenue[$month_no] = (float) $row['total_revenue'];
    $monthly_orders[$month_no] = (int) $row['total_orders'];
}
$month_labels = [];
$month_revenue_values = [];
$month_order_values = [];
for ($month = 1; $month <= 12; $month++) {
    $month_labels[] = date('M', mktime(0, 0, 0, $month, 1, $year));
    $month_revenue_values[] = round($monthly_revenue[$month], 2);
    $month_order_values[] = $monthly_orders[$month];
}

$yearly_labels = [];
$yearly_revenue_values = [];
$yearly_order_values = [];
$yearly_res = mysqli_query(
    $con,
    "SELECT YEAR($dateColumn) AS year_no, COUNT(*) AS total_orders, COALESCE(SUM($amountColumn), 0) AS total_revenue
     FROM book_form
     GROUP BY YEAR($dateColumn)
     ORDER BY YEAR($dateColumn) ASC"
);
while ($row = mysqli_fetch_assoc($yearly_res)) {
    $yearly_labels[] = (string) $row['year_no'];
    $yearly_revenue_values[] = round((float) $row['total_revenue'], 2);
    $yearly_order_values[] = (int) $row['total_orders'];
}
if (count($yearly_labels) === 0) {
    $yearly_labels = [(string) date('Y')];
    $yearly_revenue_values = [0];
    $yearly_order_values = [0];
}

$quarter_labels = ['Q1', 'Q2', 'Q3', 'Q4'];
$quarter_revenue = [0, 0, 0, 0];
$quarter_res = mysqli_query(
    $con,
    "SELECT QUARTER($dateColumn) AS quarter_no, COALESCE(SUM($amountColumn), 0) AS total_revenue
     FROM book_form
     GROUP BY QUARTER($dateColumn)
     ORDER BY QUARTER($dateColumn)"
);
while ($row = mysqli_fetch_assoc($quarter_res)) {
    $quarter_index = max(1, (int) $row['quarter_no']) - 1;
    $quarter_revenue[$quarter_index] = round((float) $row['total_revenue'], 2);
}

$top_products_res = mysqli_query($con, "SELECT wname, COUNT(*) AS cnt, COALESCE(SUM($amountColumn), 0) AS rev FROM book_form GROUP BY wname ORDER BY rev DESC LIMIT 5");
$top_names = [];
$top_revenues = [];
$top_counts = [];
while ($row = mysqli_fetch_assoc($top_products_res)) {
    $top_names[] = $row['wname'];
    $top_revenues[] = (float) $row['rev'];
    $top_counts[] = (int) $row['cnt'];
}

$city_res = mysqli_query($con, "SELECT city, COUNT(*) AS cnt FROM book_form GROUP BY city ORDER BY cnt DESC LIMIT 6");
$city_labels = [];
$city_counts = [];
while ($row = mysqli_fetch_assoc($city_res)) {
    $city_labels[] = $row['city'];
    $city_counts[] = (int) $row['cnt'];
}

$all_orders_res = mysqli_query($con, "SELECT * FROM book_form ORDER BY id DESC");
$available_years_res = mysqli_query($con, "SELECT DISTINCT YEAR($dateColumn) AS year_no FROM book_form ORDER BY year_no DESC");
$available_years = [];
while ($row = mysqli_fetch_assoc($available_years_res)) {
    $available_years[] = (int) $row['year_no'];
}
if (!$available_years) {
    $available_years[] = (int) date('Y');
}

$chart_labels = $filter === 'monthly' ? $month_labels : ($filter === 'yearly' ? $yearly_labels : $quarter_labels);
$chart_revenue = $filter === 'monthly' ? $month_revenue_values : ($filter === 'yearly' ? $yearly_revenue_values : $quarter_revenue);
$chart_orders = $filter === 'monthly' ? $month_order_values : ($filter === 'yearly' ? $yearly_order_values : [0, 0, 0, 0]);
$chart_title = $filter === 'monthly' ? "Monthly report for $year" : ($filter === 'yearly' ? 'Year-wise business report' : 'Quarter-wise revenue overview');

if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="rymo_' . $filter . '_report.csv"');
    $out = fopen('php://output', 'w');

    if ($filter === 'monthly') {
        fputcsv($out, ['Month', 'Orders', 'Revenue']);
        foreach ($month_labels as $index => $label) {
            fputcsv($out, [$label, $month_order_values[$index], $month_revenue_values[$index]]);
        }
    } elseif ($filter === 'yearly') {
        fputcsv($out, ['Year', 'Orders', 'Revenue']);
        foreach ($yearly_labels as $index => $label) {
            fputcsv($out, [$label, $yearly_order_values[$index], $yearly_revenue_values[$index]]);
        }
    } else {
        fputcsv($out, ['Quarter', 'Revenue']);
        foreach ($quarter_labels as $index => $label) {
            fputcsv($out, [$label, $quarter_revenue[$index]]);
        }
    }
    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Advanced Reports</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background: #eef1ff; min-height: 100vh; }
    .sidebar { position: fixed; top: 0; left: 0; width: 230px; height: 100vh; background: linear-gradient(180deg, #1d1d1d, #2d2d2d); padding-top: 30px; z-index: 100; }
    .sidebar .logo { text-align: center; color: #fda283; font-size: 1.4rem; font-weight: 700; padding: 0 20px 30px; border-bottom: 1px solid #444; }
    .sidebar a { display: flex; align-items: center; gap: 12px; padding: 14px 25px; color: #ccc; text-decoration: none; transition: 0.2s; font-size: 0.95rem; }
    .sidebar a:hover, .sidebar a.active { background: rgba(253,162,131,0.15); color: #fda283; border-left: 3px solid #fda283; }
    .main-content { margin-left: 230px; padding: 30px; }
    .top-bar { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px; }
    .top-card { background: linear-gradient(135deg, #1d1d1d, #6c42f8); color: #fff; border-radius: 16px; padding: 28px; flex: 1; }
    .top-card h2 { font-weight: 700; margin-bottom: 8px; }
    .top-card p { margin: 0; color: rgba(255,255,255,0.84); }
    .btn-export { background: #fff; color: #1d1d1d; border: none; padding: 11px 18px; border-radius: 10px; font-size: 0.92rem; cursor: pointer; text-decoration: none; font-weight: 700; }
    .stat-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; margin-bottom: 24px; }
    .stat-box { background: #fff; border-radius: 14px; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.07); border-left: 4px solid #6c42f8; }
    .stat-box:nth-child(2) { border-left-color: #fda283; }
    .stat-box:nth-child(3) { border-left-color: #28a745; }
    .stat-box:nth-child(4) { border-left-color: #17a2b8; }
    .stat-box:nth-child(5) { border-left-color: #20c997; }
    .stat-box:nth-child(6) { border-left-color: #ffc107; }
    .stat-box .val { font-size: 1.65rem; font-weight: 700; color: #1d1d1d; }
    .stat-box .lbl { font-size: 0.82rem; color: #777; margin-top: 6px; }
    .filters { background: #fff; border-radius: 14px; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.07); margin-bottom: 22px; }
    .filter-row { display: flex; flex-wrap: wrap; gap: 12px; align-items: end; }
    .filter-row label { display: block; margin-bottom: 6px; font-size: 0.84rem; color: #666; font-weight: 600; }
    .filter-row select {
      min-width: 180px;
      min-height: 46px;
      padding: 9px 14px;
      border: 1px solid #d6daf3;
      border-radius: 10px;
      font-size: 15px;
      line-height: 1.45;
      vertical-align: middle;
      box-sizing: border-box;
    }
    .filter-row .btn-filter { background: #6c42f8; color: #fff; border: none; padding: 10px 16px; border-radius: 10px; font-weight: 700; }
    .charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 25px; }
    .charts-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
    .chart-card, .orders-card { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.07); }
    .orders-card { border-top: 4px solid #fda283; }
    .chart-card h5, .orders-card h5 { font-weight: 700; color: #1d1d1d; margin-bottom: 20px; font-size: 1rem; }
    .chart-card canvas { max-height: 300px; }
    table { width: 100%; border-collapse: collapse; }
    table thead th { background: #f6f7ff; padding: 12px; font-size: 0.84rem; text-align: left; color: #555; }
    table tbody td { padding: 12px; font-size: 0.89rem; border-bottom: 1px solid #f1f2f8; }
    table tbody tr:nth-child(even) { background: #fbfcff; }
    .badge-ok,
    .badge-pending,
    .badge-processing,
    .badge-cancelled {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
      display: inline-block;
    }
    .badge-ok { background: #d4edda; color: #155724; }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-processing { background: #d1ecf1; color: #0c5460; }
    .badge-cancelled { background: #f8d7da; color: #721c24; }
    @media (max-width: 1200px) { .stat-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 900px) {
      .sidebar { display: none; }
      .main-content { margin-left: 0; }
      .charts-grid, .charts-grid-2 { grid-template-columns: 1fr; }
      .stat-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 650px) {
      .top-bar { flex-direction: column; }
      .stat-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
<div class="sidebar">
  <div class="logo"><i class="fa-solid fa-watch"></i> Rymo Admin</div>
  <a href="admin.php"><i class="fa-solid fa-table-list"></i> Customer Orders</a>
  <a href="view_users.php"><i class="fa-solid fa-users"></i> View Users</a>
  <a href="management_hub.php"><i class="fa-solid fa-grid-2"></i> Management Hub</a>
  <a href="advanced_report.php" class="active"><i class="fa-solid fa-chart-bar"></i> Advanced Reports</a>
  <a href="admin_contact.php"><i class="fa-solid fa-envelope"></i> Contact Messages</a>
  <a href="admin_feedback.php"><i class="fa-solid fa-star"></i> Feedback</a>
  <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="main-content">
  <div class="top-bar">
    <div class="top-card">
      <h2><i class="fa-solid fa-chart-pie"></i> Real-Time Sales Reports</h2>
      <p>Sales, order and city reports from saved records.</p>
    </div>
    <a href="advanced_report.php?filter=<?= urlencode($filter) ?>&year=<?= $year ?>&export=1" class="btn-export"><i class="fa-solid fa-file-export"></i> Export CSV</a>
  </div>

  <div class="stat-grid">
    <div class="stat-box"><div class="val"><?= $total_orders ?></div><div class="lbl">Total Orders</div></div>
    <div class="stat-box"><div class="val">Rs. <?= number_format($total_revenue, 0) ?></div><div class="lbl">Total Revenue</div></div>
    <div class="stat-box"><div class="val"><?= $total_customers ?></div><div class="lbl">Registered Users</div></div>
    <div class="stat-box"><div class="val"><?= $total_contacts ?></div><div class="lbl">Contact Messages</div></div>
    <div class="stat-box"><div class="val"><?= $delivered_orders ?></div><div class="lbl">Delivered Orders</div></div>
    <div class="stat-box"><div class="val"><?= $pending_orders ?></div><div class="lbl">Pending Orders</div></div>
  </div>

  <div class="filters">
    <form method="GET" action="advanced_report.php" class="filter-row">
      <div>
        <label for="filter">Report Type</label>
        <select id="filter" name="filter">
          <option value="monthly" <?= $filter === 'monthly' ? 'selected' : '' ?>>Monthly</option>
          <option value="yearly" <?= $filter === 'yearly' ? 'selected' : '' ?>>Yearly</option>
          <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>Quarterly / All Time</option>
        </select>
      </div>
      <div>
        <label for="year">Year</label>
        <select id="year" name="year">
          <?php foreach ($available_years as $report_year): ?>
            <option value="<?= $report_year ?>" <?= $year === $report_year ? 'selected' : '' ?>><?= $report_year ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <button class="btn-filter" type="submit"><i class="fa-solid fa-filter"></i> Apply Filter</button>
      </div>
    </form>
  </div>

  <div class="charts-grid">
    <div class="chart-card">
      <h5><i class="fa-solid fa-chart-line" style="color:#6c42f8"></i> <?= htmlspecialchars($chart_title) ?></h5>
      <canvas id="revenueChart"></canvas>
    </div>
    <div class="chart-card">
      <h5><i class="fa-solid fa-chart-pie" style="color:#fda283"></i> Orders by City</h5>
      <canvas id="cityChart"></canvas>
    </div>
  </div>

  <div class="charts-grid-2">
    <div class="chart-card">
      <h5><i class="fa-solid fa-trophy" style="color:#e67e22"></i> Top Products by Revenue</h5>
      <canvas id="topProductChart"></canvas>
    </div>
    <div class="chart-card">
      <h5><i class="fa-solid fa-chart-bar" style="color:#28a745"></i> Order Count Trend</h5>
      <canvas id="orderCountChart"></canvas>
    </div>
  </div>

  <div class="orders-card">
    <h5><i class="fa-solid fa-list-ul"></i> All Orders Detail</h5>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>#ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Watch</th>
            <th>Price</th>
            <th>City</th>
            <th>Order Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($ord = mysqli_fetch_assoc($all_orders_res)): ?>
          <tr>
            <td><?= (int) $ord['id'] ?></td>
            <td><?= htmlspecialchars($ord['name']) ?></td>
            <td><?= htmlspecialchars($ord['email']) ?></td>
            <td><?= htmlspecialchars($ord['wname']) ?></td>
            <td>Rs. <?= number_format((float) ($ord[$hasGrandTotal ? 'grand_total' : 'wprice'] ?? 0), 2) ?></td>
            <td><?= htmlspecialchars($ord['city']) ?></td>
            <td><?= !empty($ord['order_date']) ? date('d M Y', strtotime($ord['order_date'])) : 'N/A' ?></td>
            <td>
              <?php
                $status = $ord['status'] ?? 'Pending';
                $statusClass = 'badge-pending';
                if ($status === 'Delivered') { $statusClass = 'badge-ok'; }
                elseif ($status === 'Processing') { $statusClass = 'badge-processing'; }
                elseif ($status === 'Cancelled') { $statusClass = 'badge-cancelled'; }
              ?>
              <span class="<?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
const reportLabels = <?= json_encode($chart_labels) ?>;
const revenueData = <?= json_encode($chart_revenue) ?>;
const orderData = <?= json_encode($chart_orders) ?>;
const topNames = <?= json_encode($top_names) ?>;
const topRevenue = <?= json_encode($top_revenues) ?>;
const cityLabels = <?= json_encode($city_labels) ?>;
const cityCounts = <?= json_encode($city_counts) ?>;

new Chart(document.getElementById('revenueChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: reportLabels,
        datasets: [{
            label: 'Revenue (Rs.)',
            data: revenueData,
            borderColor: '#6c42f8',
            backgroundColor: 'rgba(108,66,248,0.12)',
            borderWidth: 3,
            fill: true,
            tension: 0.35,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

new Chart(document.getElementById('cityChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: cityLabels,
        datasets: [{
            data: cityCounts,
            backgroundColor: ['#6c42f8','#fda283','#28a745','#17a2b8','#ffc107','#e74c3c'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

new Chart(document.getElementById('topProductChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: topNames,
        datasets: [{
            label: 'Revenue',
            data: topRevenue,
            backgroundColor: ['#6c42f8','#fda283','#28a745','#17a2b8','#ffc107'],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

new Chart(document.getElementById('orderCountChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: reportLabels,
        datasets: [{
            label: 'Orders',
            data: orderData,
            backgroundColor: 'rgba(40,167,69,0.85)',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
</body>
</html>
