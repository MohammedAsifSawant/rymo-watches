<?php
session_start(); require_once 'portal_helpers.php'; portal_require_management_login();
$errors=[]; $success='';
if (isset($_POST['save_complaint'])) {
    $customer_name=portal_clean(portal_post('customer_name')); $email=portal_clean(portal_post('email')); $phone=portal_clean(portal_post('phone')); $order_id=portal_clean(portal_post('order_id')); $priority=portal_clean(portal_post('priority')); $complaint_text=portal_clean(portal_post('complaint_text')); $resolution_expectation=portal_clean(portal_post('resolution_expectation'));
    foreach ([['Customer name',$customer_name],['Email',$email],['Phone',$phone],['Order ID',$order_id],['Priority',$priority],['Complaint text',$complaint_text],['Resolution expectation',$resolution_expectation]] as $item) { portal_validate_required($item[0],$item[1],$errors); }
    portal_validate_email('Email',$email,$errors); portal_validate_phone('Phone',$phone,$errors);
    if (!$errors) { mysqli_query(portal_db(),"INSERT INTO customer_complaints (customer_name, email, phone, order_id, priority, complaint_text, resolution_expectation, status) VALUES ('".portal_escape($customer_name)."','".portal_escape($email)."','".portal_escape($phone)."','".portal_escape($order_id)."','".portal_escape($priority)."','".portal_escape($complaint_text)."','".portal_escape($resolution_expectation)."','Open')"); $success='Complaint saved successfully.'; $_POST=[]; }
}
portal_render_header('Complaint Form','complaint'); ?>
<div class="portal-hero"><h1>Customer Complaint Form</h1><p>Record customer issues with priority.</p></div>
<div class="portal-form-card"><div class="portal-section-title"><i class="fa-solid fa-circle-exclamation"></i> Complaint Input Screen</div><?php portal_alerts($errors,$success); ?><form method="POST"><div class="portal-form-grid">
<div class="portal-form-group"><label>Customer Name</label><input type="text" name="customer_name" value="<?= htmlspecialchars(portal_post('customer_name')) ?>" required></div>
<div class="portal-form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars(portal_post('email')) ?>" required></div>
<div class="portal-form-group"><label>Phone</label><input type="text" name="phone" value="<?= htmlspecialchars(portal_post('phone')) ?>" maxlength="10" required></div>
<div class="portal-form-group"><label>Order ID</label><input type="text" name="order_id" value="<?= htmlspecialchars(portal_post('order_id')) ?>" required></div>
<div class="portal-form-group"><label>Priority</label><select name="priority" required><option value="">Select</option><option <?= portal_post('priority')==='High'?'selected':'' ?>>High</option><option <?= portal_post('priority')==='Medium'?'selected':'' ?>>Medium</option><option <?= portal_post('priority')==='Low'?'selected':'' ?>>Low</option></select></div>
<div class="portal-form-group full"><label>Complaint Details</label><textarea name="complaint_text" placeholder="Received damaged packaging during delivery." required><?= htmlspecialchars(portal_post('complaint_text')) ?></textarea></div>
<div class="portal-form-group full"><label>Expected Resolution</label><textarea name="resolution_expectation" placeholder="Please arrange replacement packaging or support call." required><?= htmlspecialchars(portal_post('resolution_expectation')) ?></textarea></div>
</div><div class="portal-actions"><button class="portal-btn" type="submit" name="save_complaint">Save Complaint</button></div></form></div>
<?php portal_render_footer(); ?>
