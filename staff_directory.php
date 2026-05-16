<?php
session_start();
require_once 'portal_helpers.php';
portal_require_management_login();
$con = portal_db();
$result = mysqli_query($con, "SELECT * FROM staff_accounts ORDER BY id DESC");
portal_render_header('Staff Directory', 'staff-directory');
?>
<div class="portal-hero">
    <h1>Staff Directory</h1>
    <p>View registered employees and marketplace staff details in one table.</p>
</div>
<div class="portal-card">
    <div class="portal-section-title"><i class="fa-solid fa-address-book"></i> All Staff Records</div>
    <table class="portal-table">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Department</th><th>Designation</th><th>Company</th><th>Staff Code</th></tr></thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= htmlspecialchars($row['designation']) ?></td>
                <td><?= htmlspecialchars($row['company_name']) ?></td>
                <td><?= htmlspecialchars($row['staff_code']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php portal_render_footer(); ?>
