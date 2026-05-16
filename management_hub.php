<?php
session_start();
require_once 'portal_helpers.php';
portal_require_admin();

$con = portal_db();
$counts = [
    'staff' => (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS c FROM staff_accounts"))['c'],
    'suppliers' => (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS c FROM suppliers"))['c'],
    'products' => (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS c FROM catalog_products"))['c'],
    'service' => (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS c FROM service_requests"))['c'],
    'returns' => (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS c FROM return_requests"))['c'],
    'vendors' => (int) mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS c FROM vendor_partners"))['c'],
];

portal_render_header('Management Hub', 'hub');
?>
<div class="portal-hero">
    <h1>Management Hub</h1>
    <p>Main place for admin and staff related work.</p>
</div>

<div class="portal-links" style="margin-bottom:22px;">
    <a class="portal-link-card" href="admin_dashboard.php"><i class="fa-solid fa-gauge-high"></i><h3>Admin Dashboard</h3><p>View current records and counts.</p></a>
    <a class="portal-link-card" href="product_list.php"><i class="fa-solid fa-table-list"></i><h3>Manage Products</h3><p>Update catalog visibility and stock.</p></a>
    <a class="portal-link-card" href="website_products.php"><i class="fa-solid fa-store"></i><h3>Public Catalog</h3><p>See how products appear to customers.</p></a>
</div>

<div class="portal-grid">
    <div class="portal-stat"><div class="value"><?= $counts['staff'] ?></div><div class="label">Registered Staff</div></div>
    <div class="portal-stat"><div class="value"><?= $counts['suppliers'] ?></div><div class="label">Suppliers</div></div>
    <div class="portal-stat"><div class="value"><?= $counts['products'] ?></div><div class="label">Catalog Products</div></div>
    <div class="portal-stat"><div class="value"><?= $counts['service'] ?></div><div class="label">Service Requests</div></div>
    <div class="portal-stat"><div class="value"><?= $counts['returns'] ?></div><div class="label">Return Requests</div></div>
    <div class="portal-stat"><div class="value"><?= $counts['vendors'] ?></div><div class="label">Vendor Partners</div></div>
</div>

<div class="portal-card">
    <div class="portal-section-title"><i class="fa-solid fa-layer-group"></i> Input Screens</div>
    <div class="portal-links">
        <a class="portal-link-card" href="staff_register.php"><i class="fa-solid fa-user-plus"></i><h3>Staff Registration</h3><p>Add staff information with company and department.</p></a>
        <a class="portal-link-card" href="staff_login.php"><i class="fa-solid fa-user-lock"></i><h3>Staff Login</h3><p>Login page for staff members.</p></a>
        <a class="portal-link-card" href="supplier_form.php"><i class="fa-solid fa-truck-field"></i><h3>Supplier Form</h3><p>Maintain supplier details and supply categories.</p></a>
        <a class="portal-link-card" href="product_form.php"><i class="fa-solid fa-box-open"></i><h3>Product Form</h3><p>Create product entries with SKU, price, and stock.</p></a>
        <a class="portal-link-card" href="inventory_form.php"><i class="fa-solid fa-warehouse"></i><h3>Inventory Entry</h3><p>Track quantity, warehouse, and reorder levels.</p></a>
        <a class="portal-link-card" href="return_request.php"><i class="fa-solid fa-rotate-left"></i><h3>Return Request</h3><p>Capture after-sales returns and refund needs.</p></a>
        <a class="portal-link-card" href="service_request.php"><i class="fa-solid fa-screwdriver-wrench"></i><h3>Service Request</h3><p>Collect watch repair and service issues.</p></a>
        <a class="portal-link-card" href="newsletter_form.php"><i class="fa-solid fa-envelope-open-text"></i><h3>Newsletter Form</h3><p>Add customer subscription details.</p></a>
        <a class="portal-link-card" href="staff_leave.php"><i class="fa-solid fa-calendar-days"></i><h3>Staff Leave</h3><p>Manage staff leave applications with dates.</p></a>
        <a class="portal-link-card" href="vendor_registration.php"><i class="fa-solid fa-building"></i><h3>Vendor Registration</h3><p>Register business partners and marketplaces.</p></a>
        <a class="portal-link-card" href="complaint_form.php"><i class="fa-solid fa-circle-exclamation"></i><h3>Complaint Form</h3><p>Collect complaints with priority and issue details.</p></a>
        <a class="portal-link-card" href="staff_directory.php"><i class="fa-solid fa-address-book"></i><h3>Staff Directory</h3><p>View staff records in a management table.</p></a>
    </div>
</div>
<?php portal_render_footer(); ?>
