<?php

function portal_db()
{
    static $con = null;
    if ($con === null) {
        $con = mysqli_connect('localhost', 'root', '', 'rymowatch');
        if (!$con) {
            die('Database connection failed.');
        }
    }
    return $con;
}

function portal_escape($value)
{
    return mysqli_real_escape_string(portal_db(), trim((string) $value));
}

function portal_clean($value)
{
    return trim((string) $value);
}

function portal_post($key, $default = '')
{
    return $_POST[$key] ?? $default;
}

function portal_is_admin()
{
    return !empty($_SESSION['admin_logged_in']) || (isset($_SESSION['username'], $_SESSION['password']) && $_SESSION['username'] === 'admin' && $_SESSION['password'] === 'admin');
}

function portal_is_staff()
{
    return !empty($_SESSION['staff_logged_in']) && !empty($_SESSION['staff_id']);
}

function portal_current_role()
{
    if (portal_is_admin()) {
        return 'admin';
    }
    if (portal_is_staff()) {
        return 'staff';
    }
    return 'guest';
}

function portal_require_management_login()
{
    if (!portal_is_admin() && !portal_is_staff()) {
        echo "<script>window.location.assign('login.php')</script>";
        die();
    }
}

function portal_require_admin()
{
    if (!portal_is_admin()) {
        echo "<script>window.location.assign('staff_dashboard.php')</script>";
        die();
    }
}

function portal_require_staff()
{
    if (!portal_is_staff()) {
        echo "<script>window.location.assign('staff_login.php')</script>";
        die();
    }
}

function portal_management_home()
{
    return portal_is_admin() ? 'management_hub.php' : 'staff_dashboard.php';
}

function portal_mask_account($value)
{
    $clean = preg_replace('/\s+/', '', trim((string) $value));
    $length = strlen($clean);
    if ($length <= 4) {
        return $clean;
    }
    return str_repeat('X', max(0, $length - 4)) . substr($clean, -4);
}

function portal_generate_order_number()
{
    return 'RYM' . date('Ymd') . strtoupper(substr(md5(uniqid((string) mt_rand(), true)), 0, 6));
}

function portal_table_exists($table)
{
    $tableSafe = portal_escape($table);
    $result = mysqli_query(portal_db(), "SHOW TABLES LIKE '$tableSafe'");
    return $result && mysqli_num_rows($result) > 0;
}

function portal_column_exists($table, $column)
{
    $tableSafe = portal_escape($table);
    $columnSafe = portal_escape($column);
    $result = mysqli_query(portal_db(), "SHOW COLUMNS FROM `$tableSafe` LIKE '$columnSafe'");
    return $result && mysqli_num_rows($result) > 0;
}

function portal_safe_count($table, $where = '1=1')
{
    if (!portal_table_exists($table)) {
        return 0;
    }
    $result = mysqli_query(portal_db(), "SELECT COUNT(*) AS c FROM `$table` WHERE $where");
    $row = $result ? mysqli_fetch_assoc($result) : ['c' => 0];
    return (int) ($row['c'] ?? 0);
}

function portal_payment_badge_class($status)
{
    $status = strtolower(trim((string) $status));
    if ($status === 'paid') {
        return 'paid';
    }
    if ($status === 'authorized') {
        return 'authorized';
    }
    if ($status === 'cash on delivery') {
        return 'cod';
    }
    return 'pending';
}

function portal_status_badge_class($status)
{
    $status = strtolower(trim((string) $status));
    if ($status === 'delivered') {
        return 'delivered';
    }
    if ($status === 'processing') {
        return 'processing';
    }
    if ($status === 'confirmed') {
        return 'confirmed';
    }
    return 'pending';
}

function portal_validate_required($label, $value, &$errors)
{
    if (portal_clean($value) === '') {
        $errors[] = $label . ' is required.';
    }
}

function portal_validate_email($label, $value, &$errors)
{
    if (portal_clean($value) !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid ' . strtolower($label) . '.';
    }
}

function portal_validate_phone($label, $value, &$errors)
{
    if (portal_clean($value) !== '' && !preg_match('/^[0-9]{10}$/', $value)) {
        $errors[] = $label . ' must contain exactly 10 digits.';
    }
}

function portal_validate_min_length($label, $value, $length, &$errors)
{
    if (portal_clean($value) !== '' && strlen(portal_clean($value)) < $length) {
        $errors[] = $label . ' must be at least ' . $length . ' characters.';
    }
}

function portal_validate_numeric_range($label, $value, $min, $max, &$errors)
{
    if ($value === '' || !is_numeric($value) || $value < $min || $value > $max) {
        $errors[] = $label . ' must be between ' . $min . ' and ' . $max . '.';
    }
}

function portal_render_header($title, $active = '')
{
    $role = portal_current_role();
    $menu = [
        'admin' => [
            'dashboard' => ['Dashboard', 'admin_dashboard.php', 'fa-solid fa-gauge-high'],
            'hub' => ['Management Hub', 'management_hub.php', 'fa-solid fa-grid-2'],
            'staff-register' => ['Staff Register', 'staff_register.php', 'fa-solid fa-user-plus'],
            'staff-directory' => ['Staff Directory', 'staff_directory.php', 'fa-solid fa-users'],
            'supplier' => ['Supplier', 'supplier_form.php', 'fa-solid fa-truck-field'],
            'product' => ['Product', 'product_form.php', 'fa-solid fa-box-open'],
            'product-list' => ['Product List', 'product_list.php', 'fa-solid fa-table-list'],
            'inventory' => ['Inventory', 'inventory_form.php', 'fa-solid fa-warehouse'],
            'returns' => ['Returns', 'return_request.php', 'fa-solid fa-rotate-left'],
            'service' => ['Service', 'service_request.php', 'fa-solid fa-screwdriver-wrench'],
            'newsletter' => ['Newsletter', 'newsletter_form.php', 'fa-solid fa-envelope-open-text'],
            'leave' => ['Leave', 'staff_leave.php', 'fa-solid fa-calendar-days'],
            'vendor' => ['Vendors', 'vendor_registration.php', 'fa-solid fa-building'],
            'complaint' => ['Complaints', 'complaint_form.php', 'fa-solid fa-circle-exclamation'],
            'reports' => ['Reports', 'advanced_report.php', 'fa-solid fa-chart-bar'],
        ],
        'staff' => [
            'staff-home' => ['Staff Workspace', 'staff_dashboard.php', 'fa-solid fa-briefcase'],
            'staff-directory' => ['Team Directory', 'staff_directory.php', 'fa-solid fa-users'],
            'service' => ['Service Desk', 'service_request.php', 'fa-solid fa-screwdriver-wrench'],
            'returns' => ['Return Desk', 'return_request.php', 'fa-solid fa-rotate-left'],
            'leave' => ['Leave Request', 'staff_leave.php', 'fa-solid fa-calendar-days'],
            'complaint' => ['Complaints', 'complaint_form.php', 'fa-solid fa-circle-exclamation'],
        ],
    ];
    $currentMenu = $menu[$role] ?? [];
    $roleLabel = $role === 'admin' ? 'Admin Console' : ($role === 'staff' ? 'Staff Workspace' : 'Rymo ERP');
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="css/portal.css">
</head>
<body>
    <div class="portal-sidebar">
        <div class="portal-logo"><i class="fa-solid fa-watch"></i> <?= htmlspecialchars($roleLabel) ?></div>
        <?php foreach ($currentMenu as $key => $item): ?>
            <a href="<?= $item[1] ?>" class="<?= $active === $key ? 'active' : '' ?>">
                <i class="<?= $item[2] ?>"></i> <?= htmlspecialchars($item[0]) ?>
            </a>
        <?php endforeach; ?>
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
    <div class="portal-main">
<?php
}

function portal_render_footer()
{
    echo "</div></body></html>";
}

function portal_alerts($errors, $success)
{
    if (!empty($errors)) {
        echo '<div class="portal-alert portal-alert-error"><strong>Please fix:</strong><ul>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul></div>';
    }

    if ($success !== '') {
        echo '<div class="portal-alert portal-alert-success"><i class="fa-solid fa-circle-check"></i> ' . htmlspecialchars($success) . '</div>';
    }
}
