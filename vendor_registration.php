<?php
session_start(); require_once 'portal_helpers.php'; portal_require_management_login();
$errors=[]; $success='';
if (isset($_POST['save_vendor'])) {
    $vendor_name=portal_clean(portal_post('vendor_name')); $business_type=portal_clean(portal_post('business_type')); $email=portal_clean(portal_post('email')); $phone=portal_clean(portal_post('phone')); $city=portal_clean(portal_post('city')); $website=portal_clean(portal_post('website')); $services=portal_clean(portal_post('services'));
    foreach ([['Vendor name',$vendor_name],['Business type',$business_type],['Email',$email],['Phone',$phone],['City',$city],['Services',$services]] as $item) { portal_validate_required($item[0],$item[1],$errors); }
    portal_validate_email('Email',$email,$errors); portal_validate_phone('Phone',$phone,$errors);
    if ($website !== '' && !filter_var($website, FILTER_VALIDATE_URL)) { $errors[] = 'Enter a valid website URL.'; }
    if (!$errors) { mysqli_query(portal_db(),"INSERT INTO vendor_partners (vendor_name, business_type, email, phone, city, website, services) VALUES ('".portal_escape($vendor_name)."','".portal_escape($business_type)."','".portal_escape($email)."','".portal_escape($phone)."','".portal_escape($city)."','".portal_escape($website)."','".portal_escape($services)."')"); $success='Vendor registered successfully.'; $_POST=[]; }
}
portal_render_header('Vendor Registration','vendor'); ?>
<div class="portal-hero"><h1>Vendor Registration</h1><p>Add vendor contact and service details.</p></div>
<div class="portal-form-card"><div class="portal-section-title"><i class="fa-solid fa-building"></i> Vendor Input Screen</div><?php portal_alerts($errors,$success); ?><form method="POST"><div class="portal-form-grid">
<div class="portal-form-group"><label>Vendor Name</label><input type="text" name="vendor_name" value="<?= htmlspecialchars(portal_post('vendor_name')) ?>" required></div>
<div class="portal-form-group"><label>Business Type</label><select name="business_type" required><option value="">Select</option><option <?= portal_post('business_type')==='Marketplace'?'selected':'' ?>>Marketplace</option><option <?= portal_post('business_type')==='Logistics'?'selected':'' ?>>Logistics</option><option <?= portal_post('business_type')==='Accessories'?'selected':'' ?>>Accessories</option><option <?= portal_post('business_type')==='Technology'?'selected':'' ?>>Technology</option></select></div>
<div class="portal-form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars(portal_post('email')) ?>" required></div>
<div class="portal-form-group"><label>Phone</label><input type="text" name="phone" value="<?= htmlspecialchars(portal_post('phone')) ?>" maxlength="10" required></div>
<div class="portal-form-group"><label>City</label><input type="text" name="city" value="<?= htmlspecialchars(portal_post('city')) ?>" required></div>
<div class="portal-form-group"><label>Website</label><input type="url" name="website" value="<?= htmlspecialchars(portal_post('website')) ?>"></div>
<div class="portal-form-group full"><label>Services Offered</label><textarea name="services" placeholder="Handles city delivery and return pickup support." required><?= htmlspecialchars(portal_post('services')) ?></textarea></div>
</div><div class="portal-actions"><button class="portal-btn" type="submit" name="save_vendor">Save Vendor</button></div></form></div>
<?php portal_render_footer(); ?>
