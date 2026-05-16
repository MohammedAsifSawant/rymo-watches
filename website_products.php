<?php
session_start();
$con = mysqli_connect('localhost', 'root', '', 'rymowatch');
if (!$con) {
    die('Database connection failed.');
}
$products = mysqli_query($con, "SELECT * FROM catalog_products WHERE is_active=1 AND show_on_website=1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rymo Watches Catalog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
      .catalog-header {
        padding-bottom: 8px;
      }

      .catalog-header p {
        max-width: 620px;
        color: #687083;
        margin-top: 10px;
      }

      .catalog-card {
        padding: 14px 14px 18px;
        margin-bottom: 28px;
        background: #fff;
        border: 1px solid #eef0f6;
        border-radius: 18px;
        box-shadow: 0 10px 24px rgba(18, 24, 40, 0.06);
      }

      .catalog-card:nth-child(4n + 2) {
        background: #fffaf6;
      }

      .catalog-card:nth-child(4n + 3) {
        background: #f8fbff;
      }

      .catalog-card img {
        width: 100%;
        height: 230px;
        object-fit: cover;
        border-radius: 14px;
        background: #f4f5f8;
      }

      .catalog-meta {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
        margin: 10px 0 8px;
      }

      .catalog-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 700;
        color: #334155;
        background: #eef2ff;
      }

      .catalog-badge.category-Sports { background: #e0f2fe; color: #075985; }
      .catalog-badge.category-Formal { background: #f3e8ff; color: #6b21a8; }
      .catalog-badge.category-Smart { background: #dcfce7; color: #166534; }
      .catalog-badge.category-Luxury { background: #fff7ed; color: #9a3412; }

      .catalog-desc {
        font-size: 0.88rem;
        color: #5f6677;
        min-height: 42px;
        margin: 8px 4px 12px;
      }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light py-3 fixed-top">
  <div class="container">
    <img src="images/logo1.png" alt="logo">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="bar"><i class="navbar-toggler-icon"></i></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Website Products</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<section id="featured" class="my-5 py-5">
  <div class="container mt-5 py-5 catalog-header">
    <h2 class="font-weight-bold">Watch Catalog</h2>
    <hr>
    <p>Browse watches added from the management panel. Prices, stock and product details come from the database.</p>
  </div>
  <div class="row mx-auto container-fluid">
    <?php if ($products && mysqli_num_rows($products) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($products)): ?>
        <?php $categoryClass = 'category-' . preg_replace('/[^A-Za-z0-9_-]/', '', $row['category']); ?>
        <div class="product catalog-card text-center col-lg-3 col-md-4 col-12">
          <img class="img-fluid mb-3" src="<?= htmlspecialchars($row['image_path'] ?: 'images/featured/1.jpg') ?>" alt="">
          <div class="catalog-meta">
            <span class="catalog-badge <?= htmlspecialchars($categoryClass) ?>"><?= htmlspecialchars($row['category']) ?></span>
            <span class="catalog-badge"><?= htmlspecialchars($row['brand_name']) ?></span>
          </div>
          <h5 class="p-name"><?= htmlspecialchars($row['product_name']) ?></h5>
          <h4 class="p-price">Rs. <?= number_format((float) $row['price'], 0) ?></h4>
          <p class="catalog-desc"><?= htmlspecialchars($row['description']) ?></p>
          <form method="post" action="buy.php">
            <input type="hidden" name="product_id" value="<?= (int) $row['id'] ?>">
            <button class="buy-btn" type="submit">Buy Now</button>
          </form>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center"><p>No website products available yet.</p></div>
    <?php endif; ?>
  </div>
</section>
</body>
</html>
