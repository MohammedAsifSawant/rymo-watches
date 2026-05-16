<?php
session_start(); require_once 'portal_helpers.php'; portal_require_management_login();
$errors=[]; $success='';
if (isset($_POST['save_supplier'])) {
    $supplier_name=portal_clean(portal_post('supplier_name')); $contact_person=portal_clean(portal_post('contact_person')); $email=portal_clean(portal_post('email')); $phone=portal_clean(portal_post('phone')); $company_name=portal_clean(portal_post('company_name')); $gst_number=portal_clean(portal_post('gst_number')); $address=portal_clean(portal_post('address')); $supply_type=portal_clean(portal_post('supply_type'));
    foreach ([['Supplier name',$supplier_name],['Contact person',$contact_person],['Email',$email],['Phone',$phone],['Company name',$company_name],['GST number',$gst_number],['Address',$address],['Supply type',$supply_type]] as $item) { portal_validate_required($item[0],$item[1],$errors); }
    portal_validate_email('Email',$email,$errors); portal_validate_phone('Phone',$phone,$errors); portal_validate_min_length('GST number',$gst_number,8,$errors);
    if (!$errors) { mysqli_query(portal_db(),"INSERT INTO suppliers (supplier_name, contact_person, email, phone, company_name, gst_number, address, supply_type) VALUES ('".portal_escape($supplier_name)."','".portal_escape($contact_person)."','".portal_escape($email)."','".portal_escape($phone)."','".portal_escape($company_name)."','".portal_escape($gst_number)."','".portal_escape($address)."','".portal_escape($supply_type)."')"); $success='Supplier saved successfully.'; $_POST=[]; }
}
portal_render_header('Supplier Form','supplier'); ?>
<div class="portal-hero"><h1>Supplier Registration</h1><p>Save supplier contact and supply details.</p></div>
<div class="portal-form-card"><div class="portal-section-title"><i class="fa-solid fa-truck-field"></i> Supplier Input Screen</div><?php portal_alerts($errors,$success); ?><form method="POST"><div class="portal-form-grid">
<div class="portal-form-group"><label>Supplier Name</label><input type="text" name="supplier_name" value="<?= htmlspecialchars(portal_post('supplier_name')) ?>" required></div>
<div class="portal-form-group"><label>Contact Person</label><input type="text" name="contact_person" value="<?= htmlspecialchars(portal_post('contact_person')) ?>" required></div>
<div class="portal-form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars(portal_post('email')) ?>" required></div>
<div class="portal-form-group"><label>Phone</label><input type="text" name="phone" value="<?= htmlspecialchars(portal_post('phone')) ?>" maxlength="10" required></div>
<div class="portal-form-group"><label>Company Name</label><input type="text" name="company_name" value="<?= htmlspecialchars(portal_post('company_name')) ?>" required></div>
<div class="portal-form-group"><label>GST Number</label><input type="text" name="gst_number" value="<?= htmlspecialchars(portal_post('gst_number')) ?>" required></div>
<div class="portal-form-group"><label>Supply Type</label><select name="supply_type" required><option value="">Select</option><option <?= portal_post('supply_type')==='Watches'?'selected':'' ?>>Watches</option><option <?= portal_post('supply_type')==='Packaging'?'selected':'' ?>>Packaging</option><option <?= portal_post('supply_type')==='Parts'?'selected':'' ?>>Parts</option><option <?= portal_post('supply_type')==='Accessories'?'selected':'' ?>>Accessories</option></select></div>
<div class="portal-form-group full"><label>Address</label><textarea name="address" placeholder="Plot 18, MIDC Bhosari, Pune" required><?= htmlspecialchars(portal_post('address')) ?></textarea></div>
</div><div class="portal-actions"><button class="portal-btn" type="submit" name="save_supplier">Save Supplier</button></div></form></div>
<?php portal_render_footer(); ?>
