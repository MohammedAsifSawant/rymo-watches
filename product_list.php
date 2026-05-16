<?php
session_start();
require_once 'portal_helpers.php';
portal_require_admin();
$con = portal_db();
$success = '';

if (isset($_POST['toggle_product'])) {
    $product_id = (int) ($_POST['product_id'] ?? 0);
    $field = $_POST['field'] ?? '';
    if ($product_id > 0 && in_array($field, ['show_on_website', 'is_active'], true)) {
        mysqli_query($con, "UPDATE catalog_products SET $field = IF($field=1,0,1) WHERE id=$product_id");
        $success = 'Product status updated successfully.';
    }
}

$products = mysqli_query($con, "SELECT * FROM catalog_products ORDER BY id DESC");
portal_render_header('Product List', 'product-list');
?>
<div class="portal-hero">
    <h1>Product Management</h1>
    <p>Check catalog products, stock and website status.</p>
</div>
<div class="portal-form-card">
    <?php portal_alerts([], $success); ?>
    <div class="portal-section-title"><i class="fa-solid fa-table-list"></i> Catalog Products</div>
    <table class="portal-table">
        <thead><tr><th>Name</th><th>Brand</th><th>SKU</th><th>Price</th><th>Stock</th><th>Website</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['brand_name']) ?></td>
                <td><?= htmlspecialchars($row['sku']) ?></td>
                <td>Rs. <?= number_format((float) $row['price'], 2) ?></td>
                <td><?= (int) $row['stock_level'] ?></td>
                <td><span class="portal-badge <?= !empty($row['show_on_website']) ? 'delivered' : 'low' ?>"><?= !empty($row['show_on_website']) ? 'Visible' : 'Hidden' ?></span></td>
                <td><span class="portal-badge <?= !empty($row['is_active']) ? 'paid' : 'cancelled' ?>"><?= !empty($row['is_active']) ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <form method="POST" style="display:inline-block;margin-right:8px;">
                        <input type="hidden" name="product_id" value="<?= (int) $row['id'] ?>">
                        <input type="hidden" name="field" value="show_on_website">
                        <button class="portal-btn secondary" type="submit" name="toggle_product"><?= !empty($row['show_on_website']) ? 'Hide' : 'Show' ?></button>
                    </form>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="product_id" value="<?= (int) $row['id'] ?>">
                        <input type="hidden" name="field" value="is_active">
                        <button class="portal-btn secondary" type="submit" name="toggle_product"><?= !empty($row['is_active']) ? 'Deactivate' : 'Activate' ?></button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php portal_render_footer(); ?>
