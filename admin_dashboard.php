<?php
session_start();
require_once 'portal_helpers.php';
portal_require_admin();
$con = portal_db();

$cards = [
    ['Users', portal_safe_count('regi'), 'fa-solid fa-user-group'],
    ['Orders', portal_safe_count('book_form'), 'fa-solid fa-bag-shopping'],
    ['Staff', portal_safe_count('staff_accounts'), 'fa-solid fa-users'],
    ['Products', portal_safe_count('catalog_products'), 'fa-solid fa-box-open'],
    ['Suppliers', portal_safe_count('suppliers'), 'fa-solid fa-truck-field'],
    ['Complaints', portal_safe_count('customer_complaints'), 'fa-solid fa-circle-exclamation'],
];

$recent_products = portal_table_exists('catalog_products') ? mysqli_query($con, "SELECT product_name, category, price, stock_level, show_on_website FROM catalog_products ORDER BY id DESC LIMIT 5") : false;
$recent_staff = portal_table_exists('staff_accounts') ? mysqli_query($con, "SELECT full_name, department, designation, company_name FROM staff_accounts ORDER BY id DESC LIMIT 5") : false;
$hasGrandTotal = portal_column_exists('book_form', 'grand_total');
$hasPaymentMethod = portal_column_exists('book_form', 'payment_method');
$hasPaymentStatus = portal_column_exists('book_form', 'payment_status');
$hasOrderNumber = portal_column_exists('book_form', 'order_number');
$hasOrderDate = portal_column_exists('book_form', 'order_date');
$recent_orders = portal_table_exists('book_form')
    ? mysqli_query($con, "SELECT "
        . ($hasOrderNumber ? "order_number" : "CONCAT('ORD-', id) AS order_number") . ",
        name, wname, "
        . ($hasGrandTotal ? "grand_total" : "wprice AS grand_total") . ",
        " . ($hasPaymentMethod ? "payment_method" : "'Card' AS payment_method") . ",
        " . ($hasPaymentStatus ? "payment_status" : "'Paid' AS payment_status") . ",
        status, "
        . ($hasOrderDate ? "order_date" : "NULL AS order_date") . "
        FROM book_form ORDER BY id DESC LIMIT 6")
    : false;
$payment_summary = portal_table_exists('book_form')
    ? mysqli_fetch_assoc(mysqli_query($con, "SELECT 
        SUM(CASE WHEN " . ($hasPaymentStatus ? "payment_status='Paid'" : "1=1") . " THEN " . ($hasGrandTotal ? "grand_total" : "wprice") . " ELSE 0 END) AS paid_amount,
        SUM(CASE WHEN " . ($hasPaymentStatus ? "payment_status='Pending'" : "1=0") . " THEN " . ($hasGrandTotal ? "grand_total" : "wprice") . " ELSE 0 END) AS pending_amount,
        COUNT(*) AS total_orders
    FROM book_form"))
    : ['paid_amount' => 0, 'pending_amount' => 0, 'total_orders' => 0];

portal_render_header('Admin Dashboard', 'dashboard');
?>
<div class="portal-hero">
    <h1>Admin Dashboard</h1>
    <p>Daily numbers, recent orders and quick links.</p>
</div>

<div class="portal-grid">
    <?php foreach ($cards as $card): ?>
        <div class="portal-stat">
            <div style="font-size:1.35rem;color:#5b47d1;margin-bottom:10px;"><i class="<?= $card[2] ?>"></i></div>
            <div class="value"><?= $card[1] ?></div>
            <div class="label"><?= htmlspecialchars($card[0]) ?></div>
        </div>
    <?php endforeach; ?>
</div>

<div class="portal-links" style="margin-bottom:22px;">
    <a class="portal-link-card" href="product_form.php"><i class="fa-solid fa-plus"></i><h3>Add Product</h3><p>Add a watch with price and stock.</p></a>
    <a class="portal-link-card" href="product_list.php"><i class="fa-solid fa-table-list"></i><h3>Manage Products</h3><p>Check products shown on the website.</p></a>
    <a class="portal-link-card" href="website_products.php"><i class="fa-solid fa-store"></i><h3>Website Catalog</h3><p>Open the customer product page.</p></a>
</div>

<div class="portal-grid">
    <div class="portal-stat">
        <div class="value">Rs. <?= number_format((float) ($payment_summary['paid_amount'] ?? 0), 2) ?></div>
        <div class="label">Collected Revenue</div>
    </div>
    <div class="portal-stat">
        <div class="value">Rs. <?= number_format((float) ($payment_summary['pending_amount'] ?? 0), 2) ?></div>
        <div class="label">Pending Payment Value</div>
    </div>
    <div class="portal-stat">
        <div class="value"><?= (int) ($payment_summary['total_orders'] ?? 0) ?></div>
        <div class="label">Order Records</div>
    </div>
</div>

<div class="portal-card">
    <div class="portal-section-title"><i class="fa-solid fa-boxes-stacked"></i> Recent Products</div>
    <table class="portal-table">
        <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Website</th></tr></thead>
        <tbody>
        <?php if ($recent_products && mysqli_num_rows($recent_products) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($recent_products)): ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>Rs. <?= number_format((float) $row['price'], 2) ?></td>
                <td><?= (int) $row['stock_level'] ?></td>
                <td><?= !empty($row['show_on_website']) ? 'Visible' : 'Hidden' ?></td>
            </tr>
        <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No products available yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="portal-card">
    <div class="portal-section-title"><i class="fa-solid fa-credit-card"></i> Recent Orders & Payments</div>
    <table class="portal-table">
        <thead><tr><th>Order No.</th><th>Customer</th><th>Product</th><th>Total</th><th>Method</th><th>Payment</th><th>Order Status</th><th>Date</th></tr></thead>
        <tbody>
        <?php if ($recent_orders && mysqli_num_rows($recent_orders) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($recent_orders)): ?>
            <tr>
                <td><?= htmlspecialchars($row['order_number'] ?: 'NA') ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['wname']) ?></td>
                <td>Rs. <?= number_format((float) ($row['grand_total'] ?? 0), 2) ?></td>
                <td><?= htmlspecialchars($row['payment_method'] ?? 'Card') ?></td>
                <td><span class="portal-badge <?= portal_payment_badge_class($row['payment_status'] ?? 'Pending') ?>"><?= htmlspecialchars($row['payment_status'] ?? 'Pending') ?></span></td>
                <td><span class="portal-badge <?= portal_status_badge_class($row['status'] ?? 'Pending') ?>"><?= htmlspecialchars($row['status'] ?? 'Pending') ?></span></td>
                <td><?= htmlspecialchars($row['order_date'] ?? '') ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No orders available yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="portal-card">
    <div class="portal-section-title"><i class="fa-solid fa-id-badge"></i> Recent Staff</div>
    <table class="portal-table">
        <thead><tr><th>Name</th><th>Department</th><th>Designation</th><th>Company</th></tr></thead>
        <tbody>
        <?php if ($recent_staff && mysqli_num_rows($recent_staff) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($recent_staff)): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= htmlspecialchars($row['designation']) ?></td>
                <td><?= htmlspecialchars($row['company_name']) ?></td>
            </tr>
        <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No staff records available.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php portal_render_footer(); ?>
