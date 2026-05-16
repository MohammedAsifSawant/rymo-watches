<?php
session_start(); require_once 'portal_helpers.php'; portal_require_management_login();
$errors=[]; $success='';
if (isset($_POST['save_service'])) {
    $customer_name=portal_clean(portal_post('customer_name')); $email=portal_clean(portal_post('email')); $phone=portal_clean(portal_post('phone')); $watch_model=portal_clean(portal_post('watch_model')); $issue_type=portal_clean(portal_post('issue_type')); $preferred_date=portal_clean(portal_post('preferred_date')); $description=portal_clean(portal_post('description'));
    foreach ([['Customer name',$customer_name],['Email',$email],['Phone',$phone],['Watch model',$watch_model],['Issue type',$issue_type],['Preferred date',$preferred_date],['Description',$description]] as $item) { portal_validate_required($item[0],$item[1],$errors); }
    portal_validate_email('Email',$email,$errors); portal_validate_phone('Phone',$phone,$errors);
    if (!$errors) { mysqli_query(portal_db(),"INSERT INTO service_requests (customer_name, email, phone, watch_model, issue_type, preferred_date, description) VALUES ('".portal_escape($customer_name)."','".portal_escape($email)."','".portal_escape($phone)."','".portal_escape($watch_model)."','".portal_escape($issue_type)."','".portal_escape($preferred_date)."','".portal_escape($description)."')"); $success='Service request submitted successfully.'; $_POST=[]; }
}
portal_render_header('Service Request','service'); ?>
<div class="portal-hero"><h1>Service Request Form</h1><p>Add service details for a customer watch.</p></div>
<div class="portal-form-card"><div class="portal-section-title"><i class="fa-solid fa-screwdriver-wrench"></i> Service Input Screen</div><?php portal_alerts($errors,$success); ?><form method="POST"><div class="portal-form-grid">
<div class="portal-form-group"><label>Customer Name</label><input type="text" name="customer_name" value="<?= htmlspecialchars(portal_post('customer_name')) ?>" required></div>
<div class="portal-form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars(portal_post('email')) ?>" required></div>
<div class="portal-form-group"><label>Phone</label><input type="text" name="phone" value="<?= htmlspecialchars(portal_post('phone')) ?>" maxlength="10" required></div>
<div class="portal-form-group"><label>Watch Model</label><input type="text" name="watch_model" value="<?= htmlspecialchars(portal_post('watch_model')) ?>" required></div>
<div class="portal-form-group"><label>Issue Type</label><select name="issue_type" required><option value="">Select</option><option <?= portal_post('issue_type')==='Battery'?'selected':'' ?>>Battery</option><option <?= portal_post('issue_type')==='Glass Damage'?'selected':'' ?>>Glass Damage</option><option <?= portal_post('issue_type')==='Strap Replacement'?'selected':'' ?>>Strap Replacement</option><option <?= portal_post('issue_type')==='Water Damage'?'selected':'' ?>>Water Damage</option></select></div>
<div class="portal-form-group"><label>Preferred Date</label><input type="date" name="preferred_date" value="<?= htmlspecialchars(portal_post('preferred_date')) ?>" required></div>
<div class="portal-form-group full"><label>Description</label><textarea name="description" placeholder="Battery drains too fast after charging." required><?= htmlspecialchars(portal_post('description')) ?></textarea></div>
</div><div class="portal-actions"><button class="portal-btn" type="submit" name="save_service">Save Service Request</button></div></form></div>
<?php portal_render_footer(); ?>
