<?php
session_start();
require_once 'portal_helpers.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo "<script>alert('Please login first.');window.location.assign('login.php');</script>";
    exit;
}

$con = portal_db();

function book_form_columns($con)
{
    static $columns = null;
    if ($columns !== null) {
        return $columns;
    }

    $columns = [];
    $result = mysqli_query($con, "SHOW COLUMNS FROM book_form");
    while ($result && $row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }
    return $columns;
}

function book_form_has_column($con, $column)
{
    return in_array($column, book_form_columns($con), true);
}

if (!isset($_POST['send'])) {
    echo "<script>alert('Something went wrong. Please try again.');window.location.assign('shop.php');</script>";
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$zipcode = trim($_POST['zipcode'] ?? '');
$notes = trim($_POST['notes'] ?? '');

$productName = trim($_POST['product_name'] ?? '');
$productPrice = (float) ($_POST['product_price'] ?? 0);
$taxAmount = (float) ($_POST['tax_amount'] ?? 0);
$shippingAmount = (float) ($_POST['shipping_amount'] ?? 0);
$subtotalAmount = $productPrice;
$grandTotal = $subtotalAmount + $taxAmount + $shippingAmount;

$paymentMethod = trim($_POST['payment_method'] ?? 'Card');
$orderNumber = portal_generate_order_number();
$paymentStatus = 'Pending';
$orderStatus = 'Confirmed';
$paymentAccount = '';
$transactionRef = '';
$legacyCardName = '';
$legacyCardNumber = '';
$legacyExpMonth = '';
$legacyExpYear = '';
$legacyCvv = '';

$errors = [];
foreach ([['Full name', $name], ['Email', $email], ['Address', $address], ['Phone', $phone], ['City', $city], ['State', $state], ['PIN code', $zipcode], ['Product', $productName]] as $item) {
    portal_validate_required($item[0], $item[1], $errors);
}
portal_validate_email('Email', $email, $errors);
portal_validate_phone('Phone', $phone, $errors);
if (!preg_match('/^[0-9]{6}$/', $zipcode)) {
    $errors[] = 'PIN code must contain exactly 6 digits.';
}
if ($productPrice <= 0) {
    $errors[] = 'Invalid product price.';
}

if ($paymentMethod === 'Card') {
    $cardName = trim($_POST['card_name'] ?? '');
    $cardNumberRaw = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
    $expMonth = trim($_POST['exp_month'] ?? '');
    $expYear = trim($_POST['exp_year'] ?? '');
    $cvv = preg_replace('/\D/', '', $_POST['cvv'] ?? '');
    $cardReference = trim($_POST['card_reference'] ?? '');

    if ($cardName === '' || strlen($cardName) < 3) {
        $errors[] = 'Card holder name is required.';
    }
    if (!preg_match('/^[0-9]{16}$/', $cardNumberRaw)) {
        $errors[] = 'Card number must contain exactly 16 digits.';
    }
    if ($expMonth === '') {
        $errors[] = 'Expiry month is required.';
    }
    if (!preg_match('/^[0-9]{4}$/', $expYear) || (int) $expYear < (int) date('Y')) {
        $errors[] = 'Expiry year must be valid.';
    }
    if (!preg_match('/^[0-9]{3,4}$/', $cvv)) {
        $errors[] = 'CVV must contain 3 or 4 digits.';
    }

    $paymentStatus = 'Paid';
    $paymentAccount = 'Card ' . portal_mask_account($cardNumberRaw);
    $transactionRef = $cardReference !== '' ? $cardReference : 'CARD-' . strtoupper(substr(md5($orderNumber), 0, 10));
    $legacyCardName = $cardName;
    $legacyCardNumber = portal_mask_account($cardNumberRaw);
    $legacyExpMonth = $expMonth;
    $legacyExpYear = $expYear;
} elseif ($paymentMethod === 'UPI') {
    $upiId = trim($_POST['upi_id'] ?? '');
    $upiReference = trim($_POST['upi_reference'] ?? '');
    if ($upiId === '' || strpos($upiId, '@') === false) {
        $errors[] = 'Enter a valid UPI ID.';
    }
    $paymentStatus = 'Paid';
    $paymentAccount = 'UPI ' . $upiId;
    $transactionRef = $upiReference !== '' ? $upiReference : 'UPI-' . strtoupper(substr(md5($orderNumber), 0, 10));
    $legacyCardName = 'UPI Payment';
    $legacyCardNumber = portal_mask_account($upiId);
} elseif ($paymentMethod === 'Net Banking') {
    $bankName = trim($_POST['bank_name'] ?? '');
    $bankReference = trim($_POST['bank_reference'] ?? '');
    if ($bankName === '') {
        $errors[] = 'Select a bank for net banking.';
    }
    $paymentStatus = 'Authorized';
    $paymentAccount = $bankName;
    $transactionRef = $bankReference !== '' ? $bankReference : 'NB-' . strtoupper(substr(md5($orderNumber), 0, 10));
    $legacyCardName = $bankName;
    $legacyCardNumber = 'NetBanking';
} elseif ($paymentMethod === 'Cash on Delivery') {
    $paymentStatus = 'Pending';
    $paymentAccount = 'COD';
    $transactionRef = 'COD-' . strtoupper(substr(md5($orderNumber), 0, 10));
    $legacyCardName = 'Cash on Delivery';
    $legacyCardNumber = 'COD';
} else {
    $errors[] = 'Choose a valid payment method.';
}

if (!empty($errors)) {
    echo '<script>alert("' . htmlspecialchars(implode(" ", $errors), ENT_QUOTES) . '");window.history.back();</script>';
    exit;
}

$columns = ['name', 'email', 'address', 'phone', 'city', 'state', 'zipcode', 'cardna', 'cardno', 'expmon', 'expyear', 'cvvno', 'wname', 'wprice'];
$values = [$name, $email, $address, $phone, $city, $state, $zipcode, $legacyCardName, $legacyCardNumber, $legacyExpMonth, $legacyExpYear, $legacyCvv, $productName, $productPrice];
$types = 'ssssssssssssid';

$optionalColumns = [
    'order_number' => [$orderNumber, 's'],
    'customer_username' => [$_SESSION['username'] ?? '', 's'],
    'payment_method' => [$paymentMethod, 's'],
    'payment_status' => [$paymentStatus, 's'],
    'transaction_ref' => [$transactionRef, 's'],
    'payment_account' => [$paymentAccount, 's'],
    'subtotal_amount' => [$subtotalAmount, 'd'],
    'tax_amount' => [$taxAmount, 'd'],
    'shipping_amount' => [$shippingAmount, 'd'],
    'grand_total' => [$grandTotal, 'd'],
    'notes' => [$notes, 's'],
    'status' => [$orderStatus, 's'],
];

foreach ($optionalColumns as $column => $config) {
    if (book_form_has_column($con, $column)) {
        $columns[] = $column;
        $values[] = $config[0];
        $types .= $config[1];
    }
}

$placeholders = implode(', ', array_fill(0, count($columns), '?'));
$sql = "INSERT INTO book_form (" . implode(', ', $columns) . ") VALUES ($placeholders)";
$stmt = $con->prepare($sql);
$stmt->bind_param($types, ...$values);
$stmt->execute();

$displayDate = date('d M Y, h:i A');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top, rgba(203, 235, 221, 0.75), transparent 30%),
                linear-gradient(135deg, #f7faf9 0%, #eef5fb 100%);
            color: #122033;
        }

        .receipt-shell {
            max-width: 960px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .receipt-card {
            background: #fff;
            border-radius: 30px;
            padding: 34px;
            box-shadow: 0 24px 56px rgba(31, 45, 75, 0.14);
        }

        .status-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            margin-bottom: 26px;
            flex-wrap: wrap;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #dcfce7;
            color: #166534;
            border-radius: 999px;
            padding: 12px 18px;
            font-weight: 800;
        }

        h1 {
            margin: 12px 0 10px;
            font-size: 2.15rem;
        }

        .lead {
            color: #5b6578;
            margin: 0;
            max-width: 600px;
            line-height: 1.7;
        }

        .receipt-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin: 28px 0;
        }

        .info-card {
            background: #f8fbff;
            border: 1px solid #e2eaf6;
            border-radius: 22px;
            padding: 20px;
        }

        .info-card h3 {
            margin: 0 0 14px;
            font-size: 1.05rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #e8eef8;
            font-size: 0.94rem;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row span {
            color: #607086;
        }

        .amount-box {
            background: linear-gradient(135deg, #182033, #2e426d);
            color: #fff;
            border-radius: 24px;
            padding: 24px;
        }

        .amount-box .amount-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .amount-box .amount-row.total {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.18);
            font-size: 1.08rem;
            font-weight: 800;
        }

        .next-steps {
            margin-top: 24px;
            padding: 20px 22px;
            border-radius: 20px;
            background: #fff7ed;
            color: #8a4b13;
        }

        .actions {
            display: flex;
            gap: 14px;
            margin-top: 28px;
            flex-wrap: wrap;
        }

        .actions a {
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 16px;
            font-weight: 800;
        }

        .primary {
            background: #182033;
            color: #fff;
        }

        .secondary {
            background: #edf2fb;
            color: #182033;
        }

        @media (max-width: 720px) {
            .receipt-card {
                padding: 24px;
            }

            .receipt-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-shell">
        <div class="receipt-card">
            <div class="status-head">
                <div>
                    <div class="status-badge">Order Confirmed</div>
                    <h1>Thank you for your purchase</h1>
                    <p class="lead">Your order has been saved successfully with an invoice-style payment record. This gives your project a more realistic e-commerce flow for demonstration and reporting.</p>
                </div>
                <div>
                    <strong><?= htmlspecialchars($displayDate) ?></strong>
                </div>
            </div>

            <div class="receipt-grid">
                <div class="info-card">
                    <h3>Order Snapshot</h3>
                    <div class="info-row"><span>Order Number</span><strong><?= htmlspecialchars($orderNumber) ?></strong></div>
                    <div class="info-row"><span>Customer</span><strong><?= htmlspecialchars($name) ?></strong></div>
                    <div class="info-row"><span>Product</span><strong><?= htmlspecialchars($productName) ?></strong></div>
                    <div class="info-row"><span>Payment Method</span><strong><?= htmlspecialchars($paymentMethod) ?></strong></div>
                    <div class="info-row"><span>Payment Status</span><strong><?= htmlspecialchars($paymentStatus) ?></strong></div>
                    <div class="info-row"><span>Reference</span><strong><?= htmlspecialchars($transactionRef) ?></strong></div>
                </div>

                <div class="info-card">
                    <h3>Delivery Details</h3>
                    <div class="info-row"><span>Email</span><strong><?= htmlspecialchars($email) ?></strong></div>
                    <div class="info-row"><span>Phone</span><strong><?= htmlspecialchars($phone) ?></strong></div>
                    <div class="info-row"><span>Address</span><strong><?= htmlspecialchars($address) ?></strong></div>
                    <div class="info-row"><span>City / State</span><strong><?= htmlspecialchars($city . ', ' . $state) ?></strong></div>
                    <div class="info-row"><span>PIN Code</span><strong><?= htmlspecialchars($zipcode) ?></strong></div>
                    <div class="info-row"><span>Payment Identity</span><strong><?= htmlspecialchars($paymentAccount) ?></strong></div>
                </div>
            </div>

            <div class="amount-box">
                <div class="amount-row"><span>Subtotal</span><strong>Rs. <?= number_format($subtotalAmount, 2) ?></strong></div>
                <div class="amount-row"><span>GST</span><strong>Rs. <?= number_format($taxAmount, 2) ?></strong></div>
                <div class="amount-row"><span>Shipping</span><strong><?= $shippingAmount > 0 ? 'Rs. ' . number_format($shippingAmount, 2) : 'Free' ?></strong></div>
                <div class="amount-row total"><span>Grand Total</span><strong>Rs. <?= number_format($grandTotal, 2) ?></strong></div>
            </div>

            <div class="next-steps">
                <?php if ($paymentMethod === 'Cash on Delivery'): ?>
                    Payment will be collected at delivery. Keep the order number handy for support or return requests.
                <?php else: ?>
                    Payment has been recorded in masked form for safer storage. You can now use this data in admin reports and order management screens.
                <?php endif; ?>
            </div>

            <div class="actions">
                <a class="primary" href="index.php">Back to Home</a>
                <a class="secondary" href="shop.php">Continue Shopping</a>
                <a class="secondary" href="profile.php">Open Profile</a>
            </div>
        </div>
    </div>
</body>
</html>
