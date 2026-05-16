<?php
session_start();
require_once 'portal_helpers.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo "<script>alert('Please login first.');window.location.assign('login.php');</script>";
    exit;
}

$con = portal_db();

$catalogMap = [
    'a1' => ['Rymo Steel Chronograph', 4999, 'images/featured/1.jpg', 'Premium stainless steel watch with water resistance.'],
    'a2' => ['Titan Edge Classic', 6499, 'images/wwatch/1.jpg', 'Slim formal watch designed for office wear.'],
    'a3' => ['Noise Pulse Smart Fit', 2499, 'images/smart/1.jpg', 'Smart watch with calls, steps and heart tracking.'],
    'a4' => ['Rymo Heritage Brown', 4299, 'images/wwatch/2.jpg', 'Brown leather watch made for daily formal use.'],
    'b1' => ['Rymo Urban Sport Black', 3999, 'images/gshock/2.jpg', 'Light sports watch with black silicone strap.'],
    'b2' => ['Fossil Leather Premium', 7250, 'images/premium/1.jpg', 'Classic leather strap watch for formal occasions.'],
    'b3' => ['Rymo Minimal Rose Gold', 5299, 'images/girls wear/1.jpg', 'Rose gold watch with a simple clean dial.'],
    'b4' => ['Citizen Automatic Silver', 12500, 'images/citizen/1.jpg', 'Automatic silver watch with clean metal finish.'],
    'c1' => ['Casio G-Shock Red Edition', 8999, 'images/gshock/1.jpg', 'Sports edition digital watch with shock protection.'],
    'c2' => ['Casio G-Shock Matte Black', 8299, 'images/gshock/3.jpg', 'Rugged matte black watch for travel and outdoor use.'],
    'c3' => ['Rymo Traveller Dual Time', 6999, 'images/watchwear/2.jpg', 'Dual time watch useful for travel and daily wear.'],
    'c4' => ['Rymo Ocean Blue', 5499, 'images/watchwear/1.jpg', 'Blue sports watch with water resistant casing.'],
    'd1' => ['Rado Ceramic Black', 18500, 'images/rado/1.jpg', 'Black ceramic watch with a polished finish.'],
    'd2' => ['Seiko Classic Dress', 13750, 'images/premium/3.jpg', 'Dress watch with a balanced dial and metal case.'],
    'd3' => ['Hublot Style Ceramic', 22500, 'images/hublot/1.jpg', 'Bold ceramic style watch with modern design.'],
    'd4' => ['Omega Moon Dial Premium', 25500, 'images/omega/2.jpg', 'Premium moon dial watch for special occasions.'],
    'e1' => ['Rolex Deepsea Challenge', 69999, 'images/rolex/1.jpg', 'Premium diver style watch with strong case build.'],
    'e2' => ['Rolex Sea-Dweller', 78999, 'images/rolex/2.jpg', 'Luxury watch with a clean deep-sea inspired design.'],
    'e3' => ['Rolex Air-King', 50000, 'images/rolex/3.jpg', 'Classic aviation style watch with bold dial.'],
    'e4' => ['Rolex Night Jewel', 100000, 'images/rolex/4.jpg', 'Premium occasion watch with polished finish.'],
    'f1' => ['Rado Centrix', 44999, 'images/rado/1.jpg', 'Elegant ceramic watch with a slim profile.'],
    'f2' => ['Rado Ceramic Limited', 33999, 'images/rado/2.jpg', 'Limited ceramic edition with modern bracelet.'],
    'f3' => ['Rado DiaStar Original', 22399, 'images/rado/3.jpg', 'Iconic dress watch with a strong case shape.'],
    'f4' => ['Rado True Square', 45000, 'images/rado/4.jpg', 'Square ceramic watch with premium styling.'],
    'g1' => ['Omega Tresor Quartz', 18999, 'images/omega/1.jpg', 'Slim quartz watch with a formal dial.'],
    'g2' => ['Omega Moonwatch Professional', 65000, 'images/omega/2.jpg', 'Chronograph watch inspired by moonwatch design.'],
    'g3' => ['Omega Diver 300M', 82000, 'images/omega/3.jpg', 'Luxury diver watch with sporty detailing.'],
    'g4' => ['Omega Moonwatch Classic', 35000, 'images/omega/4.jpg', 'Classic chronograph watch for collectors.'],
    'h1' => ['Citizen Eco-Drive Black', 16000, 'images/citizen/1.jpg', 'Solar powered watch with everyday comfort.'],
    'h2' => ['Citizen Automatic Silver', 19999, 'images/citizen/2.jpg', 'Automatic silver watch with clean finish.'],
    'h3' => ['Citizen Eco-Drive Leather', 16000, 'images/citizen/3.jpg', 'Eco-drive watch with leather strap.'],
    'h4' => ['Citizen Signature Blue', 24999, 'images/citizen/4.jpg', 'Signature style watch with premium blue dial.'],
    'i1' => ['Hublot Red Ceramic', 320000, 'images/hublot/1.jpg', 'Bold red ceramic watch for luxury display.'],
    'i2' => ['Hublot Unico SORAI', 18000, 'images/hublot/2.jpg', 'Modern luxury style watch with sporty case.'],
    'i3' => ['Hublot Bi-Axis Style', 13999, 'images/hublot/3.jpg', 'Statement watch with layered dial design.'],
    'i4' => ['Hublot Titanium Ceramic', 16999, 'images/hublot/4.jpg', 'Titanium ceramic watch with strong wrist presence.'],
    'j1' => ['Rymo Smart Square', 4599, 'images/smart/4.jpg', 'Square smart watch with notifications and activity tracking.'],
    'j2' => ['Boat Storm Active', 2799, 'images/smart/3.jpg', 'Affordable smart watch with fitness modes.'],
    'j3' => ['FireBolt Call Pro', 3299, 'images/smart/2.jpg', 'Smart calling watch with bright display.'],
    'j4' => ['Noise Pulse Smart Fit', 2499, 'images/smart/1.jpg', 'Smart watch with calls, steps and heart tracking.'],
];

$selectedProduct = null;
if (isset($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];
    $result = mysqli_query($con, "SELECT product_name, price, image_path, description FROM catalog_products WHERE id=$productId AND is_active=1 LIMIT 1");
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $selectedProduct = [
            'name' => $row['product_name'],
            'price' => (float) $row['price'],
            'image' => $row['image_path'] ?: 'images/featured/1.jpg',
            'description' => $row['description'],
        ];
    }
}

if ($selectedProduct === null) {
    foreach ($catalogMap as $key => $product) {
        if (isset($_POST[$key])) {
            $selectedProduct = [
                'name' => $product[0],
                'price' => (float) $product[1],
                'image' => $product[2],
                'description' => $product[3],
            ];
            break;
        }
    }
}

if ($selectedProduct === null) {
    echo "<script>alert('Please choose a product first.');window.location.assign('shop.php');</script>";
    exit;
}

$unitPrice = (float) $selectedProduct['price'];
$taxAmount = round($unitPrice * 0.18, 2);
$shippingAmount = $unitPrice >= 5000 ? 0.00 : 149.00;
$grandTotal = $unitPrice + $taxAmount + $shippingAmount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(192, 216, 255, 0.7), transparent 28%),
                radial-gradient(circle at bottom right, rgba(255, 224, 196, 0.8), transparent 26%),
                linear-gradient(135deg, #f6f8fc 0%, #eef3fb 48%, #fdf7f0 100%);
            color: #1d2433;
        }

        .checkout-shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 36px 20px 48px;
        }

        .checkout-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .brand-mark {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 0.04em;
        }

        .top-links {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .top-links a {
            text-decoration: none;
            color: #1d2433;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.82);
            box-shadow: 0 10px 22px rgba(36, 47, 71, 0.08);
        }

        .checkout-layout {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 26px;
        }

        .panel {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 28px;
            box-shadow: 0 22px 50px rgba(39, 50, 77, 0.12);
        }

        .summary-panel {
            padding: 22px;
            position: sticky;
            top: 24px;
            align-self: start;
        }

        .summary-panel img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 20px;
            margin-bottom: 18px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #6b7387;
            margin-bottom: 12px;
        }

        .summary-panel h1 {
            margin: 0 0 8px;
            font-size: 2rem;
            line-height: 1.1;
        }

        .summary-panel p {
            margin: 0 0 18px;
            color: #5c6477;
            font-size: 0.95rem;
            line-height: 1.7;
        }

        .price-chip {
            display: inline-flex;
            align-items: center;
            padding: 12px 18px;
            border-radius: 999px;
            background: #182033;
            color: #fff;
            font-weight: 800;
            margin-bottom: 22px;
        }

        .line-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            color: #465066;
            font-size: 0.95rem;
        }

        .line-item.total {
            padding-top: 14px;
            border-top: 1px solid #e5eaf4;
            margin-top: 12px;
            font-size: 1.05rem;
            font-weight: 800;
            color: #111827;
        }

        .trust-strip {
            margin-top: 20px;
            padding: 16px;
            border-radius: 20px;
            background: linear-gradient(135deg, #eff5ff, #fff4eb);
        }

        .trust-strip strong {
            display: block;
            margin-bottom: 6px;
        }

        .form-panel {
            padding: 30px;
        }

        .checkout-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            margin-bottom: 22px;
        }

        .checkout-header h2 {
            margin: 0;
            font-size: 1.8rem;
        }

        .checkout-header p {
            margin: 6px 0 0;
            color: #5c6477;
        }

        .secure-badge {
            background: #182033;
            color: #fff;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 0.9rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .section-title {
            margin: 28px 0 14px;
            font-size: 1.05rem;
            font-weight: 800;
            color: #1f2937;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #374151;
        }

        input, select, textarea {
            width: 100%;
            border: 1px solid #dce3ef;
            border-radius: 16px;
            min-height: 48px;
            padding: 11px 16px;
            font: inherit;
            font-size: 15px;
            line-height: 1.45;
            vertical-align: middle;
            background: #fcfdff;
            box-sizing: border-box;
        }

        select {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        textarea {
            min-height: 118px;
            padding-top: 12px;
            padding-bottom: 12px;
            resize: vertical;
        }

        .method-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 14px;
        }

        .method-card {
            position: relative;
        }

        .method-card input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .method-card label {
            height: 100%;
            margin: 0;
            padding: 18px;
            border-radius: 20px;
            border: 1px solid #dce3ef;
            background: #fff;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .method-card input:checked + label {
            border-color: #14213d;
            box-shadow: 0 16px 30px rgba(20, 33, 61, 0.12);
            transform: translateY(-2px);
        }

        .method-card strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 6px;
        }

        .method-card span {
            color: #667085;
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .payment-panel {
            display: none;
            margin-top: 10px;
            padding: 18px;
            border-radius: 20px;
            background: #f8fbff;
            border: 1px solid #dde7f5;
        }

        .payment-panel.active {
            display: block;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-top: 28px;
            flex-wrap: wrap;
        }

        .btn-primary, .btn-secondary {
            text-decoration: none;
            border: none;
            border-radius: 16px;
            padding: 15px 22px;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #182033, #2f436f);
            color: #fff;
            min-width: 220px;
        }

        .btn-secondary {
            background: #edf2fb;
            color: #182033;
        }

        .microcopy {
            margin-top: 12px;
            color: #6b7387;
            font-size: 0.84rem;
        }

        @media (max-width: 980px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }

            .summary-panel {
                position: static;
            }
        }

        @media (max-width: 700px) {
            .form-grid,
            .method-grid {
                grid-template-columns: 1fr;
            }

            .form-panel {
                padding: 22px;
            }

            .checkout-header {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="checkout-shell">
        <div class="checkout-topbar">
            <div class="brand-mark">Rymo Watches</div>
            <div class="top-links">
                <a href="index.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="profile.php">My Profile</a>
            </div>
        </div>

        <div class="checkout-layout">
            <aside class="panel summary-panel">
                <div class="eyebrow">Selected Product</div>
                <img src="<?= htmlspecialchars($selectedProduct['image']) ?>" alt="Product image">
                <h1><?= htmlspecialchars($selectedProduct['name']) ?></h1>
                <p><?= htmlspecialchars($selectedProduct['description']) ?></p>
                <div class="price-chip">Rs. <?= number_format($unitPrice, 2) ?></div>

                <div class="line-item"><span>Base price</span><strong>Rs. <?= number_format($unitPrice, 2) ?></strong></div>
                <div class="line-item"><span>GST (18%)</span><strong>Rs. <?= number_format($taxAmount, 2) ?></strong></div>
                <div class="line-item"><span>Shipping</span><strong><?= $shippingAmount > 0 ? 'Rs. ' . number_format($shippingAmount, 2) : 'Free' ?></strong></div>
                <div class="line-item total"><span>Estimated total</span><strong>Rs. <?= number_format($grandTotal, 2) ?></strong></div>

                <div class="trust-strip">
                    <strong>Checkout Highlights</strong>
                    <div>Realistic payment methods, invoice-ready order records, and masked payment storage for safer project demonstration.</div>
                </div>
            </aside>

            <section class="panel form-panel">
                <div class="checkout-header">
                    <div>
                        <div class="eyebrow">Secure Checkout</div>
                        <h2>Complete your order</h2>
                        <p>Fill in delivery details, choose a payment option, and generate a proper order record.</p>
                    </div>
                    <div class="secure-badge">Encrypted Order Flow</div>
                </div>

                <form action="book_form.php" method="post" id="checkoutForm" novalidate>
                    <div class="section-title">Delivery Information</div>
                    <div class="form-grid">
                        <div class="field">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" minlength="3" maxlength="60" required>
                        </div>
                        <div class="field">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="field full">
                            <label for="address">Street Address</label>
                            <input type="text" id="address" name="address" minlength="10" maxlength="150" required>
                        </div>
                        <div class="field">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" pattern="[0-9]{10}" maxlength="10" required>
                        </div>
                        <div class="field">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="field">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state" required>
                        </div>
                        <div class="field">
                            <label for="zipcode">PIN Code</label>
                            <input type="text" id="zipcode" name="zipcode" pattern="[0-9]{6}" maxlength="6" required>
                        </div>
                        <div class="field full">
                            <label for="notes">Order Notes</label>
                            <textarea id="notes" name="notes" placeholder="Optional delivery instructions, gift note, or preferred delivery time."></textarea>
                        </div>
                    </div>

                    <div class="section-title">Payment Method</div>
                    <div class="method-grid">
                        <div class="method-card">
                            <input type="radio" id="method_card" name="payment_method" value="Card" checked>
                            <label for="method_card">
                                <strong>Card Payment</strong>
                                <span>Visa, Mastercard, and RuPay style checkout with secure masking.</span>
                            </label>
                        </div>
                        <div class="method-card">
                            <input type="radio" id="method_upi" name="payment_method" value="UPI">
                            <label for="method_upi">
                                <strong>UPI</strong>
                                <span>Collect via UPI ID with transaction reference and instant confirmation.</span>
                            </label>
                        </div>
                        <div class="method-card">
                            <input type="radio" id="method_netbanking" name="payment_method" value="Net Banking">
                            <label for="method_netbanking">
                                <strong>Net Banking</strong>
                                <span>Bank transfer style authorization with reference-based audit trail.</span>
                            </label>
                        </div>
                        <div class="method-card">
                            <input type="radio" id="method_cod" name="payment_method" value="Cash on Delivery">
                            <label for="method_cod">
                                <strong>Cash on Delivery</strong>
                                <span>Book the order now and collect payment at doorstep delivery.</span>
                            </label>
                        </div>
                    </div>

                    <div class="payment-panel active" data-method-panel="Card">
                        <div class="form-grid">
                            <div class="field">
                                <label for="card_name">Name on Card</label>
                                <input type="text" id="card_name" name="card_name" maxlength="60">
                            </div>
                            <div class="field">
                                <label for="card_number">Card Number</label>
                                <input type="text" id="card_number" name="card_number" inputmode="numeric" maxlength="19" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="field">
                                <label for="exp_month">Expiry Month</label>
                                <input type="text" id="exp_month" name="exp_month" maxlength="9" placeholder="MM">
                            </div>
                            <div class="field">
                                <label for="exp_year">Expiry Year</label>
                                <input type="number" id="exp_year" name="exp_year" min="<?= date('Y') ?>" max="<?= date('Y') + 15 ?>">
                            </div>
                            <div class="field">
                                <label for="cvv">CVV</label>
                                <input type="password" id="cvv" name="cvv" inputmode="numeric" maxlength="4" placeholder="123">
                            </div>
                            <div class="field">
                                <label for="card_reference">Authorization Reference</label>
                                <input type="text" id="card_reference" name="card_reference" maxlength="30" placeholder="AUTO-CARD-REF">
                            </div>
                        </div>
                    </div>

                    <div class="payment-panel" data-method-panel="UPI">
                        <div class="form-grid">
                            <div class="field">
                                <label for="upi_id">UPI ID</label>
                                <input type="text" id="upi_id" name="upi_id" maxlength="80" placeholder="customer@upi">
                            </div>
                            <div class="field">
                                <label for="upi_reference">UPI Reference</label>
                                <input type="text" id="upi_reference" name="upi_reference" maxlength="30" placeholder="UPI123456789">
                            </div>
                        </div>
                    </div>

                    <div class="payment-panel" data-method-panel="Net Banking">
                        <div class="form-grid">
                            <div class="field">
                                <label for="bank_name">Bank Name</label>
                                <select id="bank_name" name="bank_name">
                                    <option value="">Select bank</option>
                                    <option>State Bank of India</option>
                                    <option>HDFC Bank</option>
                                    <option>ICICI Bank</option>
                                    <option>Axis Bank</option>
                                    <option>Bank of Baroda</option>
                                </select>
                            </div>
                            <div class="field">
                                <label for="bank_reference">Transaction Reference</label>
                                <input type="text" id="bank_reference" name="bank_reference" maxlength="30" placeholder="NBK89456321">
                            </div>
                        </div>
                    </div>

                    <div class="payment-panel" data-method-panel="Cash on Delivery">
                        <div class="form-grid">
                            <div class="field full">
                                <label for="cod_note">Confirmation Note</label>
                                <input type="text" id="cod_note" name="cod_note" value="Customer will pay at the time of delivery." readonly>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($selectedProduct['name']) ?>">
                    <input type="hidden" name="product_price" value="<?= number_format($unitPrice, 2, '.', '') ?>">
                    <input type="hidden" name="product_image" value="<?= htmlspecialchars($selectedProduct['image']) ?>">
                    <input type="hidden" name="tax_amount" value="<?= number_format($taxAmount, 2, '.', '') ?>">
                    <input type="hidden" name="shipping_amount" value="<?= number_format($shippingAmount, 2, '.', '') ?>">

                    <div class="actions">
                        <a href="javascript:history.back()" class="btn-secondary">Back to Products</a>
                        <button type="submit" class="btn-primary" name="send">Place Secure Order</button>
                    </div>
                    <div class="microcopy">Your project now records payment method, order number, payment reference, and masked payment identity instead of saving raw sensitive card details.</div>
                </form>
            </section>
        </div>
    </div>

    <script>
        const methodInputs = document.querySelectorAll('input[name="payment_method"]');
        const methodPanels = document.querySelectorAll('[data-method-panel]');

        function updatePanels() {
            const selected = document.querySelector('input[name="payment_method"]:checked').value;
            methodPanels.forEach((panel) => {
                panel.classList.toggle('active', panel.getAttribute('data-method-panel') === selected);
            });
        }

        methodInputs.forEach((input) => input.addEventListener('change', updatePanels));
        updatePanels();

        const cardNumber = document.getElementById('card_number');
        if (cardNumber) {
            cardNumber.addEventListener('input', function () {
                let value = this.value.replace(/\D/g, '').slice(0, 16);
                this.value = value.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
            });
        }
    </script>
</body>
</html>
