<?php
session_start();
require_once 'portal_helpers.php';
portal_require_staff();

$con = portal_db();
$staffId = (int) ($_SESSION['staff_id'] ?? 0);
$staffName = $_SESSION['staff_name'] ?? 'Team Member';

$staff = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM staff_accounts WHERE id=$staffId LIMIT 1"));
$serviceCount = portal_safe_count('service_requests');
$returnCount = portal_safe_count('return_requests');
$complaintCount = portal_safe_count('customer_complaints');
$leaveCount = 0;
if (!empty($staff['staff_code'])) {
    $staffCode = portal_escape($staff['staff_code']);
    $leaveCount = portal_safe_count('staff_leave_requests', "staff_code='$staffCode'");
}
$recentLeaves = !empty($staff['staff_code'])
    ? mysqli_query($con, "SELECT leave_type, from_date, to_date, status FROM staff_leave_requests WHERE staff_code='" . portal_escape($staff['staff_code']) . "' ORDER BY id DESC LIMIT 5")
    : false;
$recentServices = portal_table_exists('service_requests') ? mysqli_query($con, "SELECT customer_name, watch_model, issue_type, preferred_date FROM service_requests ORDER BY id DESC LIMIT 5") : false;

portal_render_header('Staff Dashboard', 'staff-home');
?>
<div class="portal-hero">
    <h1>Staff Operations Dashboard</h1>
    <p>Welcome <?= htmlspecialchars($staffName) ?>. Check service, returns and leave details here.</p>
</div>

<div class="portal-grid">
    <div class="portal-stat"><div class="value"><?= $serviceCount ?></div><div class="label">Service Requests Logged</div></div>
    <div class="portal-stat"><div class="value"><?= $returnCount ?></div><div class="label">Return Requests Raised</div></div>
    <div class="portal-stat"><div class="value"><?= $complaintCount ?></div><div class="label">Customer Complaints</div></div>
    <div class="portal-stat"><div class="value"><?= $leaveCount ?></div><div class="label">Your Leave Applications</div></div>
    <div class="portal-stat"><div class="value"><?= htmlspecialchars($staff['department'] ?? 'NA') ?></div><div class="label">Department</div></div>
    <div class="portal-stat"><div class="value"><?= htmlspecialchars($staff['designation'] ?? 'NA') ?></div><div class="label">Designation</div></div>
</div>

<div class="portal-links" style="margin-bottom:22px;">
    <a class="portal-link-card" href="service_request.php"><i class="fa-solid fa-screwdriver-wrench"></i><h3>Log Service Ticket</h3><p>Add a customer repair request.</p></a>
    <a class="portal-link-card" href="return_request.php"><i class="fa-solid fa-rotate-left"></i><h3>Create Return Case</h3><p>Add a return or refund request.</p></a>
    <a class="portal-link-card" href="staff_leave.php"><i class="fa-solid fa-calendar-days"></i><h3>Apply For Leave</h3><p>Submit leave dates for approval.</p></a>
</div>

<div class="portal-card">
    <div class="portal-section-title"><i class="fa-solid fa-user"></i> Staff Profile</div>
    <div class="portal-grid">
        <div class="portal-stat"><div class="value"><?= htmlspecialchars($staff['full_name'] ?? $staffName) ?></div><div class="label">Employee Name</div></div>
        <div class="portal-stat"><div class="value"><?= htmlspecialchars($staff['staff_code'] ?? 'NA') ?></div><div class="label">Staff Code</div></div>
        <div class="portal-stat"><div class="value"><?= htmlspecialchars($staff['company_name'] ?? 'Rymo Watches') ?></div><div class="label">Company</div></div>
    </div>
</div>

<div class="portal-card">
    <div class="portal-section-title"><i class="fa-solid fa-file-signature"></i> Your Recent Leave Requests</div>
    <table class="portal-table">
        <thead><tr><th>Type</th><th>From</th><th>To</th><th>Status</th></tr></thead>
        <tbody>
        <?php if ($recentLeaves && mysqli_num_rows($recentLeaves) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($recentLeaves)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['leave_type']) ?></td>
                    <td><?= htmlspecialchars($row['from_date']) ?></td>
                    <td><?= htmlspecialchars($row['to_date']) ?></td>
                    <td><span class="portal-badge <?= portal_status_badge_class($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No leave applications yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="portal-card">
    <div class="portal-section-title"><i class="fa-solid fa-headset"></i> Recent Service Desk Activity</div>
    <table class="portal-table">
        <thead><tr><th>Customer</th><th>Watch Model</th><th>Issue</th><th>Preferred Date</th></tr></thead>
        <tbody>
        <?php if ($recentServices && mysqli_num_rows($recentServices) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($recentServices)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['watch_model']) ?></td>
                    <td><?= htmlspecialchars($row['issue_type']) ?></td>
                    <td><?= htmlspecialchars($row['preferred_date']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No service requests available.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php portal_render_footer(); ?>
