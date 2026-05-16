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

$status_message = '';

if (isset($_POST['update_status'])) {
    $order_id = (int) ($_POST['order_id'] ?? 0);
    $new_status = mysqli_real_escape_string($con, trim($_POST['new_status'] ?? 'Pending'));
    $allowed_statuses = ['Pending', 'Processing', 'Delivered', 'Cancelled'];

    if ($order_id > 0 && in_array($new_status, $allowed_statuses, true)) {
        mysqli_query($con, "UPDATE book_form SET status='$new_status' WHERE id=$order_id");
        $status_message = 'Order status updated successfully.';
    }
}

$search = trim($_GET['search'] ?? '');
$status_filter = trim($_GET['status'] ?? '');
$city_filter = trim($_GET['city'] ?? '');

$conditions = [];
if ($search !== '') {
    $safe_search = mysqli_real_escape_string($con, $search);
    $conditions[] = "(name LIKE '%$safe_search%' OR email LIKE '%$safe_search%' OR wname LIKE '%$safe_search%')";
}
if ($status_filter !== '') {
    $safe_status = mysqli_real_escape_string($con, $status_filter);
    $conditions[] = "status = '$safe_status'";
}
if ($city_filter !== '') {
    $safe_city = mysqli_real_escape_string($con, $city_filter);
    $conditions[] = "city = '$safe_city'";
}

$where_sql = $conditions ? ('WHERE ' . implode(' AND ', $conditions)) : '';
$orders_sql = "SELECT * FROM book_form $where_sql ORDER BY id DESC";
$result = mysqli_query($con, $orders_sql);

$total_orders = (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM book_form"))['total'];
$pending_orders = (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM book_form WHERE status='Pending'"))['total'];
$processing_orders = (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM book_form WHERE status='Processing'"))['total'];
$delivered_orders = (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM book_form WHERE status='Delivered'"))['total'];

$cities_res = mysqli_query($con, "SELECT DISTINCT city FROM book_form WHERE city <> '' ORDER BY city ASC");
$cities = [];
while ($city_row = mysqli_fetch_assoc($cities_res)) {
    $cities[] = $city_row['city'];
}

function status_badge_class($status)
{
    if ($status === 'Delivered') {
        return 'badge-delivered';
    }
    if ($status === 'Processing') {
        return 'badge-processing';
    }
    if ($status === 'Cancelled') {
        return 'badge-cancelled';
    }
    return 'badge-pending';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #eef1ff; min-height: 100vh; color: #1d1d1d; }
        .sidebar { position: fixed; top: 0; left: 0; width: 230px; height: 100vh; background: linear-gradient(180deg, #1d1d1d, #2d2d2d); padding-top: 28px; z-index: 100; }
        .sidebar .logo { text-align: center; color: #fda283; font-size: 1.35rem; font-weight: 700; padding: 0 20px 28px; border-bottom: 1px solid #444; }
        .sidebar a { display: flex; align-items: center; gap: 12px; padding: 14px 24px; color: #ccc; text-decoration: none; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(253, 162, 131, 0.14); color: #fda283; border-left: 3px solid #fda283; }
        .main { margin-left: 230px; padding: 28px; }
        .hero { background: linear-gradient(135deg, #1d1d1d, #6c42f8); color: #fff; border-radius: 16px; padding: 28px; display: flex; justify-content: space-between; gap: 20px; align-items: center; margin-bottom: 24px; }
        .hero h1 { font-size: 1.9rem; margin-bottom: 8px; }
        .hero p { color: rgba(255,255,255,0.84); font-size: 0.95rem; }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .hero-actions a { background: #fff; color: #1d1d1d; text-decoration: none; padding: 10px 16px; border-radius: 10px; font-weight: 600; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 24px; }
        .stat-card { background: #fff; border-radius: 14px; padding: 22px; box-shadow: 0 8px 24px rgba(0,0,0,0.07); }
        .stat-card .icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; margin-bottom: 12px; }
        .icon-dark { background: linear-gradient(135deg, #1d1d1d, #555); }
        .icon-orange { background: linear-gradient(135deg, #fda283, #e67e22); }
        .icon-blue { background: linear-gradient(135deg, #17a2b8, #2980b9); }
        .icon-green { background: linear-gradient(135deg, #28a745, #20c997); }
        .stat-card .value { font-size: 1.9rem; font-weight: 700; }
        .stat-card .label { color: #777; font-size: 0.88rem; margin-top: 4px; }
        .toolbar, .table-card { background: #fff; border-radius: 14px; box-shadow: 0 8px 24px rgba(0,0,0,0.07); }
        .toolbar { padding: 22px; margin-bottom: 22px; }
        .toolbar h3 { margin-bottom: 14px; font-size: 1.05rem; }
        .filter-form { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 12px; align-items: end; }
        .filter-form label { display: block; margin-bottom: 6px; font-size: 0.84rem; color: #666; font-weight: 600; }
        .filter-form input, .filter-form select {
            width: 100%;
            min-height: 46px;
            padding: 10px 14px;
            border: 1px solid #d8dcf2;
            border-radius: 10px;
            font-size: 15px;
            line-height: 1.45;
            vertical-align: middle;
            box-sizing: border-box;
        }
        .filter-form select {
            padding-top: 9px;
            padding-bottom: 9px;
        }
        .btn { border: none; border-radius: 10px; padding: 11px 16px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; justify-content: center; }
        .btn-primary { background: #6c42f8; color: #fff; }
        .btn-light { background: #f3f5ff; color: #333; }
        .alert-ok { background: #d4edda; color: #155724; padding: 12px 14px; border-radius: 10px; margin-bottom: 18px; }
        .table-card { padding: 22px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f5f6ff; color: #555; font-size: 0.84rem; text-align: left; padding: 12px; }
        td { padding: 12px; border-bottom: 1px solid #f0f1f8; font-size: 0.9rem; vertical-align: top; }
        tr:hover { background: #fafbff; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 0.76rem; font-weight: 700; }
        .badge-delivered { background: #d4edda; color: #155724; }
        .badge-processing { background: #d1ecf1; color: #0c5460; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
        .status-form { display: flex; gap: 8px; align-items: center; }
        .status-form select {
            min-width: 145px;
            min-height: 42px;
            padding: 9px 12px;
            border-radius: 8px;
            border: 1px solid #d8dcf2;
            font-size: 14px;
            line-height: 1.4;
            vertical-align: middle;
            box-sizing: border-box;
        }
        .status-form button { padding: 8px 12px; font-size: 0.82rem; }
        .small { font-size: 0.82rem; color: #777; }
        .empty-state { text-align: center; padding: 44px 20px; color: #888; }
        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .filter-form { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 900px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
            .hero { flex-direction: column; align-items: flex-start; }
        }
        @media (max-width: 650px) {
            .stats-grid, .filter-form { grid-template-columns: 1fr; }
            .status-form { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-watch"></i> Rymo Admin</div>
        <a href="admin.php" class="active"><i class="fa-solid fa-table-list"></i> Customer Orders</a>
        <a href="view_users.php"><i class="fa-solid fa-users"></i> View Users</a>
        <a href="management_hub.php"><i class="fa-solid fa-grid-2"></i> Management Hub</a>
        <a href="advanced_report.php"><i class="fa-solid fa-chart-bar"></i> Reports</a>
        <a href="admin_contact.php"><i class="fa-solid fa-envelope"></i> Contact Messages</a>
        <a href="admin_feedback.php"><i class="fa-solid fa-star"></i> Feedback</a>
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>

    <div class="main">
        <div class="hero">
            <div>
                <h1>Customer Order Management</h1>
                <p>Check orders and update their current status.</p>
            </div>
            <div class="hero-actions">
                <a href="advanced_report.php"><i class="fa-solid fa-chart-line"></i> Open Reports</a>
                <a href="admin_feedback.php"><i class="fa-solid fa-comments"></i> View Feedback</a>
            </div>
        </div>

        <?php if ($status_message !== ''): ?>
            <div class="alert-ok"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($status_message) ?></div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon icon-dark"><i class="fa-solid fa-bag-shopping"></i></div>
                <div class="value"><?= $total_orders ?></div>
                <div class="label">Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="icon icon-orange"><i class="fa-solid fa-hourglass-half"></i></div>
                <div class="value"><?= $pending_orders ?></div>
                <div class="label">Pending Orders</div>
            </div>
            <div class="stat-card">
                <div class="icon icon-blue"><i class="fa-solid fa-truck-fast"></i></div>
                <div class="value"><?= $processing_orders ?></div>
                <div class="label">Processing Orders</div>
            </div>
            <div class="stat-card">
                <div class="icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
                <div class="value"><?= $delivered_orders ?></div>
                <div class="label">Delivered Orders</div>
            </div>
        </div>

        <div class="toolbar">
            <h3><i class="fa-solid fa-filter"></i> Search and Filter</h3>
            <form class="filter-form" method="GET" action="admin.php">
                <div>
                    <label for="search">Search customer, email, or product</label>
                    <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search order details">
                </div>
                <div>
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All Status</option>
                        <option value="Pending" <?= $status_filter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Processing" <?= $status_filter === 'Processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="Delivered" <?= $status_filter === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                        <option value="Cancelled" <?= $status_filter === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="city">City</label>
                    <select id="city" name="city">
                        <option value="">All Cities</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= htmlspecialchars($city) ?>" <?= $city_filter === $city ? 'selected' : '' ?>><?= htmlspecialchars($city) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Apply</button>
                    <a class="btn btn-light" href="admin.php" style="margin-top:10px;"><i class="fa-solid fa-rotate"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="table-card">
            <h3 style="margin-bottom:16px;"><i class="fa-solid fa-list"></i> Orders Table</h3>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>#<?= (int) $row['id'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['name']) ?></strong>
                                    <div class="small"><?= htmlspecialchars($row['city']) ?>, <?= htmlspecialchars($row['state']) ?></div>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['email']) ?><br>
                                    <span class="small"><?= htmlspecialchars($row['phone']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($row['address']) ?><div class="small">PIN: <?= htmlspecialchars($row['zipcode']) ?></div></td>
                                <td><?= htmlspecialchars($row['wname']) ?></td>
                                <td>Rs. <?= number_format((float) $row['wprice'], 2) ?></td>
                                <td><?= !empty($row['order_date']) ? date('d M Y', strtotime($row['order_date'])) : 'N/A' ?></td>
                                <td><span class="badge <?= status_badge_class($row['status'] ?? 'Pending') ?>"><?= htmlspecialchars($row['status'] ?? 'Pending') ?></span></td>
                                <td>
                                    <form class="status-form" method="POST" action="admin.php">
                                        <input type="hidden" name="order_id" value="<?= (int) $row['id'] ?>">
                                        <select name="new_status">
                                            <option value="Pending" <?= ($row['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Processing" <?= ($row['status'] ?? '') === 'Processing' ? 'selected' : '' ?>>Processing</option>
                                            <option value="Delivered" <?= ($row['status'] ?? '') === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                            <option value="Cancelled" <?= ($row['status'] ?? '') === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <button class="btn btn-primary" type="submit" name="update_status">Save</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fa-solid fa-box-open" style="font-size:2rem;margin-bottom:10px;"></i>
                    <p>No orders matched your filter.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
