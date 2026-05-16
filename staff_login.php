<?php
session_start();
require_once 'portal_helpers.php';

$errors = [];
$success = '';

if (isset($_POST['login_staff'])) {
    $email = portal_clean(portal_post('email'));
    $staff_code = portal_clean(portal_post('staff_code'));
    $password = portal_clean(portal_post('password'));

    portal_validate_required('Email', $email, $errors);
    portal_validate_required('Staff code', $staff_code, $errors);
    portal_validate_required('Password', $password, $errors);
    portal_validate_email('Email', $email, $errors);

    if (empty($errors)) {
        $con = portal_db();
        $email_safe = portal_escape($email);
        $code_safe = portal_escape($staff_code);
        $pass_safe = portal_escape($password);
        $result = mysqli_query($con, "SELECT * FROM staff_accounts WHERE email='$email_safe' AND staff_code='$code_safe' AND password='$pass_safe' LIMIT 1");
        if ($result && mysqli_num_rows($result) === 1) {
            $staff = mysqli_fetch_assoc($result);
            $_SESSION['staff_logged_in'] = true;
            $_SESSION['staff_id'] = $staff['id'];
            $_SESSION['staff_name'] = $staff['full_name'];
            header('Location: staff_dashboard.php');
            exit;
        }
        $errors[] = 'Invalid staff login credentials.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="css/portal.css">
</head>
<body>
    <div class="portal-main" style="margin-left:0;max-width:760px;margin:0 auto;padding-top:40px;">
        <div class="portal-hero">
            <h1>Staff Login</h1>
            <p>Login for staff members.</p>
        </div>
        <div class="portal-form-card">
            <div class="portal-section-title"><i class="fa-solid fa-user-lock"></i> Login Credentials</div>
            <?php portal_alerts($errors, $success); ?>
            <form method="POST" action="staff_login.php">
                <div class="portal-form-grid">
                    <div class="portal-form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars(portal_post('email')) ?>" required>
                    </div>
                    <div class="portal-form-group">
                        <label>Staff Code</label>
                        <input type="text" name="staff_code" value="<?= htmlspecialchars(portal_post('staff_code')) ?>" required>
                    </div>
                    <div class="portal-form-group full">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                </div>
                <div class="portal-actions">
                    <button type="submit" name="login_staff" class="portal-btn">Login as Staff</button>
                    <a href="login.php" class="portal-btn secondary">Back to Main Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
