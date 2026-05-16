<?php
session_start(); require_once 'portal_helpers.php'; portal_require_admin();
$errors=[]; $success='';
if (isset($_POST['save_product'])) {
    $product_name=portal_clean(portal_post('product_name')); $brand_name=portal_clean(portal_post('brand_name')); $category=portal_clean(portal_post('category')); $sku=portal_clean(portal_post('sku')); $price=portal_clean(portal_post('price')); $stock_level=portal_clean(portal_post('stock_level')); $description=portal_clean(portal_post('description')); $image_path=portal_clean(portal_post('image_path')); $show_on_website=(int)!empty($_POST['show_on_website']); $is_active=(int)!empty($_POST['is_active']);
    foreach ([['Product name',$product_name],['Brand name',$brand_name],['Category',$category],['SKU',$sku],['Price',$price],['Stock level',$stock_level],['Description',$description],['Image path',$image_path]] as $item) { portal_validate_required($item[0],$item[1],$errors); }
    portal_validate_numeric_range('Price',$price,100,1000000,$errors); portal_validate_numeric_range('Stock level',$stock_level,0,100000,$errors);
    if (!$errors) { mysqli_query(portal_db(),"INSERT INTO catalog_products (product_name, brand_name, category, sku, price, stock_level, description, image_path, show_on_website, is_active) VALUES ('".portal_escape($product_name)."','".portal_escape($brand_name)."','".portal_escape($category)."','".portal_escape(strtoupper($sku))."',".(float)$price.",".(int)$stock_level.",'".portal_escape($description)."','".portal_escape($image_path)."',$show_on_website,$is_active)"); $success='Product created successfully.'; $_POST=[]; }
}
portal_render_header('Product Form','product'); ?>
<div class="portal-hero"><h1>Product Master Form</h1><p>Add watch details for catalog and stock.</p></div>
<div class="portal-form-card"><div class="portal-section-title"><i class="fa-solid fa-box-open"></i> Product Input Screen</div><?php portal_alerts($errors,$success); ?><form method="POST"><div class="portal-form-grid">
<div class="portal-form-group"><label>Product Name</label><input type="text" name="product_name" value="<?= htmlspecialchars(portal_post('product_name')) ?>" required></div>
<div class="portal-form-group"><label>Brand Name</label><input type="text" name="brand_name" value="<?= htmlspecialchars(portal_post('brand_name')) ?>" required></div>
<div class="portal-form-group"><label>Category</label><select name="category" required><option value="">Select</option><option <?= portal_post('category')==='Luxury'?'selected':'' ?>>Luxury</option><option <?= portal_post('category')==='Smart'?'selected':'' ?>>Smart</option><option <?= portal_post('category')==='Formal'?'selected':'' ?>>Formal</option><option <?= portal_post('category')==='Sports'?'selected':'' ?>>Sports</option></select></div>
<div class="portal-form-group"><label>SKU</label><input type="text" name="sku" value="<?= htmlspecialchars(portal_post('sku')) ?>" required></div>
<div class="portal-form-group"><label>Price</label><input type="number" name="price" min="100" step="0.01" value="<?= htmlspecialchars(portal_post('price')) ?>" required></div>
<div class="portal-form-group"><label>Stock Level</label><input type="number" name="stock_level" min="0" value="<?= htmlspecialchars(portal_post('stock_level')) ?>" required></div>
<div class="portal-form-group"><label>Image Path</label><input type="text" name="image_path" value="<?= htmlspecialchars(portal_post('image_path', 'images/featured/1.jpg')) ?>" required></div>
<div class="portal-form-group"><label>Website Visibility</label><select name="show_on_website"><option value="1" <?= !empty(portal_post('show_on_website', '1')) ? 'selected' : '' ?>>Show on website</option><option value="0" <?= portal_post('show_on_website')==='0' ? 'selected' : '' ?>>Keep hidden</option></select></div>
<div class="portal-form-group"><label>Product Status</label><select name="is_active"><option value="1" <?= !empty(portal_post('is_active', '1')) ? 'selected' : '' ?>>Active</option><option value="0" <?= portal_post('is_active')==='0' ? 'selected' : '' ?>>Inactive</option></select></div>
<div class="portal-form-group full"><label>Description</label><textarea name="description" placeholder="Clean stainless steel chronograph suitable for office and daily wear." required><?= htmlspecialchars(portal_post('description')) ?></textarea></div>
</div><div class="portal-actions"><button class="portal-btn" type="submit" name="save_product">Save Product</button></div></form></div>
<?php portal_render_footer(); ?>
