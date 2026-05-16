<?php
session_start(); require_once 'portal_helpers.php'; portal_require_management_login();
$errors=[]; $success='';
if (isset($_POST['save_return'])) {
    $customer_name=portal_clean(portal_post('customer_name')); $email=portal_clean(portal_post('email')); $order_id=portal_clean(portal_post('order_id')); $product_name=portal_clean(portal_post('product_name')); $return_reason=portal_clean(portal_post('return_reason')); $refund_mode=portal_clean(portal_post('refund_mode'));
    foreach ([['Customer name',$customer_name],['Email',$email],['Order ID',$order_id],['Product name',$product_name],['Return reason',$return_reason],['Refund mode',$refund_mode]] as $item) { portal_validate_required($item[0],$item[1],$errors); }
    portal_validate_email('Email',$email,$errors); portal_validate_min_length('Order ID',$order_id,4,$errors);
    if (!$errors) { mysqli_query(portal_db(),"INSERT INTO return_requests (customer_name, email, order_id, product_name, return_reason, refund_mode, status) VALUES ('".portal_escape($customer_name)."','".portal_escape($email)."','".portal_escape($order_id)."','".portal_escape($product_name)."','".portal_escape($return_reason)."','".portal_escape($refund_mode)."','Pending')"); $success='Return request saved successfully.'; $_POST=[]; }
}
portal_render_header('Return Request','returns'); ?>
<div class="portal-hero"><h1>Return Request Form</h1><p>Save return details for an existing order.</p></div>
<div class="portal-form-card"><div class="portal-section-title"><i class="fa-solid fa-rotate-left"></i> Return Input Screen</div><?php portal_alerts($errors,$success); ?><form method="POST"><div class="portal-form-grid">
<div class="portal-form-group"><label>Customer Name</label><input type="text" name="customer_name" value="<?= htmlspecialchars(portal_post('customer_name')) ?>" required></div>
<div class="portal-form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars(portal_post('email')) ?>" required></div>
<div class="portal-form-group"><label>Order ID</label><input type="text" name="order_id" value="<?= htmlspecialchars(portal_post('order_id')) ?>" required></div>
<div class="portal-form-group"><label>Product Name</label><input type="text" name="product_name" value="<?= htmlspecialchars(portal_post('product_name')) ?>" required></div>
<div class="portal-form-group"><label>Refund Mode</label><select name="refund_mode" required><option value="">Select</option><option <?= portal_post('refund_mode')==='Bank Transfer'?'selected':'' ?>>Bank Transfer</option><option <?= portal_post('refund_mode')==='Wallet'?'selected':'' ?>>Wallet</option><option <?= portal_post('refund_mode')==='Original Payment Method'?'selected':'' ?>>Original Payment Method</option></select></div>
<div class="portal-form-group full"><label>Return Reason</label><textarea name="return_reason" placeholder="Need replacement for defective strap." required><?= htmlspecialchars(portal_post('return_reason')) ?></textarea></div>
</div><div class="portal-actions"><button class="portal-btn" type="submit" name="save_return">Save Return Request</button></div></form></div>
<?php portal_render_footer(); ?>
