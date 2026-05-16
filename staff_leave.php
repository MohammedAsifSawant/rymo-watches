<?php
session_start(); require_once 'portal_helpers.php'; portal_require_management_login();
$errors=[]; $success='';
if (isset($_POST['save_leave'])) {
    $staff_name=portal_clean(portal_post('staff_name')); $staff_code=portal_clean(portal_post('staff_code')); $department=portal_clean(portal_post('department')); $leave_type=portal_clean(portal_post('leave_type')); $from_date=portal_clean(portal_post('from_date')); $to_date=portal_clean(portal_post('to_date')); $reason=portal_clean(portal_post('reason'));
    foreach ([['Staff name',$staff_name],['Staff code',$staff_code],['Department',$department],['Leave type',$leave_type],['From date',$from_date],['To date',$to_date],['Reason',$reason]] as $item) { portal_validate_required($item[0],$item[1],$errors); }
    if (!$errors && strtotime($to_date) < strtotime($from_date)) { $errors[] = 'To date must be after from date.'; }
    if (!$errors) { mysqli_query(portal_db(),"INSERT INTO staff_leave_requests (staff_name, staff_code, department, leave_type, from_date, to_date, reason, status) VALUES ('".portal_escape($staff_name)."','".portal_escape($staff_code)."','".portal_escape($department)."','".portal_escape($leave_type)."','".portal_escape($from_date)."','".portal_escape($to_date)."','".portal_escape($reason)."','Pending')"); $success='Leave request saved.'; $_POST=[]; }
}
portal_render_header('Staff Leave','leave'); ?>
<div class="portal-hero"><h1>Staff Leave Request</h1><p>Add an HR workflow with date validation and approval-ready leave records.</p></div>
<div class="portal-form-card"><div class="portal-section-title"><i class="fa-solid fa-calendar-days"></i> Leave Input Screen</div><?php portal_alerts($errors,$success); ?><form method="POST"><div class="portal-form-grid">
<div class="portal-form-group"><label>Staff Name</label><input type="text" name="staff_name" value="<?= htmlspecialchars(portal_post('staff_name', $_SESSION['staff_name'] ?? '')) ?>" required></div>
<div class="portal-form-group"><label>Staff Code</label><input type="text" name="staff_code" value="<?= htmlspecialchars(portal_post('staff_code')) ?>" required></div>
<div class="portal-form-group"><label>Department</label><select name="department" required><option value="">Select</option><option <?= portal_post('department')==='Sales'?'selected':'' ?>>Sales</option><option <?= portal_post('department')==='Support'?'selected':'' ?>>Support</option><option <?= portal_post('department')==='Warehouse'?'selected':'' ?>>Warehouse</option><option <?= portal_post('department')==='Marketplace'?'selected':'' ?>>Marketplace</option></select></div>
<div class="portal-form-group"><label>Leave Type</label><select name="leave_type" required><option value="">Select</option><option <?= portal_post('leave_type')==='Casual'?'selected':'' ?>>Casual</option><option <?= portal_post('leave_type')==='Sick'?'selected':'' ?>>Sick</option><option <?= portal_post('leave_type')==='Earned'?'selected':'' ?>>Earned</option></select></div>
<div class="portal-form-group"><label>From Date</label><input type="date" name="from_date" value="<?= htmlspecialchars(portal_post('from_date')) ?>" required></div>
<div class="portal-form-group"><label>To Date</label><input type="date" name="to_date" value="<?= htmlspecialchars(portal_post('to_date')) ?>" required></div>
<div class="portal-form-group full"><label>Reason</label><textarea name="reason" required><?= htmlspecialchars(portal_post('reason')) ?></textarea></div>
</div><div class="portal-actions"><button class="portal-btn" type="submit" name="save_leave">Save Leave Request</button></div></form></div>
<?php portal_render_footer(); ?>
