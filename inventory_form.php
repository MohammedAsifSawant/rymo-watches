<?php
session_start(); require_once 'portal_helpers.php'; portal_require_management_login();
$errors=[]; $success='';
if (isset($_POST['save_inventory'])) {
    $warehouse_name=portal_clean(portal_post('warehouse_name')); $item_name=portal_clean(portal_post('item_name')); $item_code=portal_clean(portal_post('item_code')); $quantity=portal_clean(portal_post('quantity')); $unit_cost=portal_clean(portal_post('unit_cost')); $reorder_level=portal_clean(portal_post('reorder_level')); $updated_by=portal_clean(portal_post('updated_by'));
    foreach ([['Warehouse name',$warehouse_name],['Item name',$item_name],['Item code',$item_code],['Quantity',$quantity],['Unit cost',$unit_cost],['Reorder level',$reorder_level],['Updated by',$updated_by]] as $item) { portal_validate_required($item[0],$item[1],$errors); }
    portal_validate_numeric_range('Quantity',$quantity,0,100000,$errors); portal_validate_numeric_range('Unit cost',$unit_cost,1,1000000,$errors); portal_validate_numeric_range('Reorder level',$reorder_level,0,100000,$errors);
    if (!$errors) { mysqli_query(portal_db(),"INSERT INTO inventory_entries (warehouse_name, item_name, item_code, quantity, unit_cost, reorder_level, updated_by) VALUES ('".portal_escape($warehouse_name)."','".portal_escape($item_name)."','".portal_escape(strtoupper($item_code))."',".(int)$quantity.",".(float)$unit_cost.",".(int)$reorder_level.",'".portal_escape($updated_by)."')"); $success='Inventory record saved.'; $_POST=[]; }
}
portal_render_header('Inventory Form','inventory'); ?>
<div class="portal-hero"><h1>Inventory Entry Screen</h1><p>Update stock and reorder levels.</p></div>
<div class="portal-form-card"><div class="portal-section-title"><i class="fa-solid fa-warehouse"></i> Inventory Input Screen</div><?php portal_alerts($errors,$success); ?><form method="POST"><div class="portal-form-grid">
<div class="portal-form-group"><label>Warehouse Name</label><input type="text" name="warehouse_name" value="<?= htmlspecialchars(portal_post('warehouse_name')) ?>" required></div>
<div class="portal-form-group"><label>Item Name</label><input type="text" name="item_name" value="<?= htmlspecialchars(portal_post('item_name')) ?>" required></div>
<div class="portal-form-group"><label>Item Code</label><input type="text" name="item_code" value="<?= htmlspecialchars(portal_post('item_code')) ?>" required></div>
<div class="portal-form-group"><label>Quantity</label><input type="number" name="quantity" min="0" value="<?= htmlspecialchars(portal_post('quantity')) ?>" required></div>
<div class="portal-form-group"><label>Unit Cost</label><input type="number" name="unit_cost" min="1" step="0.01" value="<?= htmlspecialchars(portal_post('unit_cost')) ?>" required></div>
<div class="portal-form-group"><label>Reorder Level</label><input type="number" name="reorder_level" min="0" value="<?= htmlspecialchars(portal_post('reorder_level')) ?>" required></div>
<div class="portal-form-group full"><label>Updated By</label><input type="text" name="updated_by" value="<?= htmlspecialchars(portal_post('updated_by', $_SESSION['staff_name'] ?? 'Admin')) ?>" required></div>
</div><div class="portal-actions"><button class="portal-btn" type="submit" name="save_inventory">Save Inventory</button></div></form></div>
<?php portal_render_footer(); ?>
