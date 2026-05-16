<?php
session_start(); require_once 'portal_helpers.php'; portal_require_management_login();
$errors=[]; $success='';
if (isset($_POST['save_newsletter'])) {
    $full_name=portal_clean(portal_post('full_name')); $email=portal_clean(portal_post('email')); $city=portal_clean(portal_post('city')); $interest_area=portal_clean(portal_post('interest_area')); $frequency=portal_clean(portal_post('frequency'));
    foreach ([['Full name',$full_name],['Email',$email],['City',$city],['Interest area',$interest_area],['Frequency',$frequency]] as $item) { portal_validate_required($item[0],$item[1],$errors); }
    portal_validate_email('Email',$email,$errors);
    if (!$errors) { mysqli_query(portal_db(),"INSERT INTO newsletter_subscribers (full_name, email, city, interest_area, frequency) VALUES ('".portal_escape($full_name)."','".portal_escape($email)."','".portal_escape($city)."','".portal_escape($interest_area)."','".portal_escape($frequency)."')"); $success='Newsletter subscriber added.'; $_POST=[]; }
}
portal_render_header('Newsletter Form','newsletter'); ?>
<div class="portal-hero"><h1>Newsletter Subscription Form</h1><p>Add customer subscription details.</p></div>
<div class="portal-form-card"><div class="portal-section-title"><i class="fa-solid fa-envelope-open-text"></i> Newsletter Input Screen</div><?php portal_alerts($errors,$success); ?><form method="POST"><div class="portal-form-grid">
<div class="portal-form-group"><label>Full Name</label><input type="text" name="full_name" value="<?= htmlspecialchars(portal_post('full_name')) ?>" required></div>
<div class="portal-form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars(portal_post('email')) ?>" required></div>
<div class="portal-form-group"><label>City</label><input type="text" name="city" value="<?= htmlspecialchars(portal_post('city')) ?>" required></div>
<div class="portal-form-group"><label>Interest Area</label><select name="interest_area" required><option value="">Select</option><option <?= portal_post('interest_area')==='Luxury Watches'?'selected':'' ?>>Luxury Watches</option><option <?= portal_post('interest_area')==='Smart Watches'?'selected':'' ?>>Smart Watches</option><option <?= portal_post('interest_area')==='Offers'?'selected':'' ?>>Offers</option><option <?= portal_post('interest_area')==='Service Updates'?'selected':'' ?>>Service Updates</option></select></div>
<div class="portal-form-group full"><label>Frequency</label><select name="frequency" required><option value="">Select</option><option <?= portal_post('frequency')==='Weekly'?'selected':'' ?>>Weekly</option><option <?= portal_post('frequency')==='Monthly'?'selected':'' ?>>Monthly</option><option <?= portal_post('frequency')==='Quarterly'?'selected':'' ?>>Quarterly</option></select></div>
</div><div class="portal-actions"><button class="portal-btn" type="submit" name="save_newsletter">Save Subscription</button></div></form></div>
<?php portal_render_footer(); ?>
