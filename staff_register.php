<?php
session_start();
require_once 'portal_helpers.php';
portal_require_management_login();

$errors = [];
$success = '';

if (isset($_POST['save_staff'])) {
    $full_name = portal_clean(portal_post('full_name'));
    $email = portal_clean(portal_post('email'));
    $phone = portal_clean(portal_post('phone'));
    $department = portal_clean(portal_post('department'));
    $designation = portal_clean(portal_post('designation'));
    $company_name = portal_clean(portal_post('company_name'));
    $staff_code = strtoupper(portal_clean(portal_post('staff_code')));
    $password = portal_clean(portal_post('password'));

    portal_validate_required('Full name', $full_name, $errors);
    portal_validate_required('Email', $email, $errors);
    portal_validate_required('Phone', $phone, $errors);
    portal_validate_required('Department', $department, $errors);
    portal_validate_required('Designation', $designation, $errors);
    portal_validate_required('Company name', $company_name, $errors);
    portal_validate_required('Staff code', $staff_code, $errors);
    portal_validate_required('Password', $password, $errors);
    portal_validate_email('Email', $email, $errors);
    portal_validate_phone('Phone', $phone, $errors);
    portal_validate_min_length('Password', $password, 6, $errors);

    if (empty($errors)) {
        $con = portal_db();
        $email_safe = portal_escape($email);
        $staff_code_safe = portal_escape($staff_code);
        $check = mysqli_query($con, "SELECT id FROM staff_accounts WHERE email='$email_safe' OR staff_code='$staff_code_safe' LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            $errors[] = 'Staff email or staff code already exists.';
        } else {
            mysqli_query(
                $con,
                "INSERT INTO staff_accounts (full_name, email, phone, department, designation, company_name, staff_code, password)
                 VALUES ('" . portal_escape($full_name) . "', '$email_safe', '" . portal_escape($phone) . "', '" . portal_escape($department) . "', '" . portal_escape($designation) . "', '" . portal_escape($company_name) . "', '$staff_code_safe', '" . portal_escape($password) . "')"
            );
            $success = 'Staff account created successfully.';
            $_POST = [];
        }
    }
}

portal_render_header('Staff Registration', 'staff-register');
?>
<div class="portal-hero">
    <h1>Staff Registration</h1>
    <p>Add staff details for login and daily work.</p>
</div>
<div class="portal-form-card">
    <div class="portal-section-title"><i class="fa-solid fa-user-plus"></i> Staff Information Form</div>
    <?php portal_alerts($errors, $success); ?>
    <form method="POST" action="staff_register.php">
        <div class="portal-form-grid">
            <div class="portal-form-group"><label>Full Name</label><input type="text" name="full_name" value="<?= htmlspecialchars(portal_post('full_name')) ?>" required></div>
            <div class="portal-form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars(portal_post('email')) ?>" required></div>
            <div class="portal-form-group"><label>Phone</label><input type="text" name="phone" value="<?= htmlspecialchars(portal_post('phone')) ?>" maxlength="10" required></div>
            <div class="portal-form-group"><label>Department</label><select name="department" required><option value="">Select department</option><option <?= portal_post('department') === 'Sales' ? 'selected' : '' ?>>Sales</option><option <?= portal_post('department') === 'Support' ? 'selected' : '' ?>>Support</option><option <?= portal_post('department') === 'Warehouse' ? 'selected' : '' ?>>Warehouse</option><option <?= portal_post('department') === 'Marketplace' ? 'selected' : '' ?>>Marketplace</option><option <?= portal_post('department') === 'HR' ? 'selected' : '' ?>>HR</option></select></div>
            <div class="portal-form-group"><label>Designation</label><input type="text" name="designation" value="<?= htmlspecialchars(portal_post('designation')) ?>" required></div>
            <div class="portal-form-group"><label>Company / Team</label><select name="company_name" required><option value="">Select company</option><option <?= portal_post('company_name') === 'Rymo' ? 'selected' : '' ?>>Rymo</option><option <?= portal_post('company_name') === 'Amazon' ? 'selected' : '' ?>>Amazon</option><option <?= portal_post('company_name') === 'Flipkart' ? 'selected' : '' ?>>Flipkart</option></select></div>
            <div class="portal-form-group"><label>Staff Code</label><input type="text" name="staff_code" value="<?= htmlspecialchars(portal_post('staff_code')) ?>" required></div>
            <div class="portal-form-group"><label>Password</label><input type="password" name="password" required></div>
        </div>
        <div class="portal-actions"><button type="submit" name="save_staff" class="portal-btn">Save Staff</button></div>
    </form>
</div>
<?php portal_render_footer(); ?>
