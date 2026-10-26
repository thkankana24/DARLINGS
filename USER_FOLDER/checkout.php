<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/sql/config.php';
require_once __DIR__ . '/sql/customer_session.php';

$sessionId = session_id();
$cart = [];
$total = 0;
$message = '';
$messageType = 'info';
$indianStates = [
    'Andhra Pradesh',
    'Arunachal Pradesh',
    'Assam',
    'Bihar',
    'Chhattisgarh',
    'Goa',
    'Gujarat',
    'Haryana',
    'Himachal Pradesh',
    'Jharkhand',
    'Karnataka',
    'Kerala',
    'Madhya Pradesh',
    'Maharashtra',
    'Manipur',
    'Meghalaya',
    'Mizoram',
    'Nagaland',
    'Odisha',
    'Punjab',
    'Rajasthan',
    'Sikkim',
    'Tamil Nadu',
    'Telangana',
    'Tripura',
    'Uttar Pradesh',
    'Uttarakhand',
    'West Bengal',
    'Andaman and Nicobar Islands',
    'Chandigarh',
    'Dadra and Nagar Haveli and Daman and Diu',
    'Delhi',
    'Jammu and Kashmir',
    'Ladakh',
    'Lakshadweep',
    'Puducherry'
];

$locations = [
    'Andhra Pradesh' => [
        'Visakhapatnam' => ['530001', '530016'],
        'Vijayawada' => ['520001', '520010']
    ],
    'Arunachal Pradesh' => [],
    'Assam' => [
        'Guwahati' => ['781001', '781005', '781022']
    ],
    'Bihar' => [
        'Patna' => ['800001', '800004', '800020']
    ],
    'Chhattisgarh' => [],
    'Goa' => [],
    'Gujarat' => [
        'Ahmedabad' => ['380001', '380009', '380015'],
        'Surat' => ['395001', '395007', '395010']
    ],
    'Haryana' => [
        'Gurugram' => ['122001', '122002', '122018'],
        'Faridabad' => ['121001', '121003']
    ],
    'Himachal Pradesh' => [],
    'Jharkhand' => [],
    'Karnataka' => [
        'Bengaluru' => ['560001', '560034', '560037', '560068'],
        'Mysuru' => ['570001', '570008', '570023']
    ],
    'Kerala' => [
        'Kochi' => ['682001', '682016', '682020'],
        'Thiruvananthapuram' => ['695001', '695014']
    ],
    'Madhya Pradesh' => [
        'Bhopal' => ['462001', '462016'],
        'Indore' => ['452001', '452010']
    ],
    'Maharashtra' => [
        'Mumbai' => ['400001', '400050', '400053', '400076'],
        'Pune' => ['411001', '411004', '411014', '411038'],
        'Nagpur' => ['440001', '440010', '440022']
    ],
    'Manipur' => [],
    'Meghalaya' => [],
    'Mizoram' => [],
    'Nagaland' => [],
    'Odisha' => [
        'Bhubaneswar' => ['751001', '751003', '751024']
    ],
    'Punjab' => [
        'Amritsar' => ['143001', '143002'],
        'Ludhiana' => ['141001', '141003']
    ],
    'Rajasthan' => [
        'Jaipur' => ['302001', '302004', '302017'],
        'Udaipur' => ['313001', '313002']
    ],
    'Sikkim' => [],
    'Tamil Nadu' => [
        'Chennai' => ['600001', '600004', '600028', '600040'],
        'Coimbatore' => ['641001', '641018', '641037']
    ],
    'Telangana' => [
        'Hyderabad' => ['500001', '500016', '500032', '500081']
    ],
    'Tripura' => [],
    'Uttar Pradesh' => [
        'Lucknow' => ['226001', '226010', '226016'],
        'Noida' => ['201301', '201303', '201304'],
        'Varanasi' => ['221001', '221002']
    ],
    'Uttarakhand' => [],
    'West Bengal' => [
        'Kolkata' => [
            '700001',
            '700016',
            '700019',
            '700029',
            '700091',
            '700070',
            '700084',
            '700107',
            '700108',
            '700109',
            '700110',
            '700111',
            '700112',
            '700113',
            '700114',
            '700115',
            '700116',
            '700117',
            '700118',
            '700119',
            '700120',
            '700121',
            '700122',
            '700123',
            '700124',
            '700125',
            '700126',
            '700127',
            '700128',
            '700129'
        ],
        'Howrah' => ['711101', '711102', '711103'],
        'Durgapur' => ['713201', '713202', '713203'],
        'Siliguri' => ['734001', '734003', '734005']
    ],
    'Andaman and Nicobar Islands' => [],
    'Chandigarh' => [
        'Chandigarh' => ['160001', '160017', '160022']
    ],
    'Dadra and Nagar Haveli and Daman and Diu' => [],
    'Delhi' => [
        'New Delhi' => ['110001', '110003', '110011', '110016'],
        'Delhi' => ['110006', '110008', '110018', '110092']
    ],
    'Jammu and Kashmir' => [],
    'Ladakh' => [],
    'Lakshadweep' => [],
    'Puducherry' => []
];

$allowedPayments = [
    'cod',
    'google_pay',
    'phonepe',
    'paytm',
    'bhim_upi',
    'card',
    'gift_card',
    'emi'
];

/*
|--------------------------------------------------------------------------
| Read cart from database
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT c.product_id, c.quantity, c.size,
            p.product_name AS name, p.price, p.stock
     FROM cart c
     INNER JOIN products_admin p ON p.id = c.product_id
     WHERE c.session_id = ?"
);

if ($stmt) {
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($item = $result->fetch_assoc()) {
        $item['quantity'] = (int) $item['quantity'];
        $item['price'] = (float) $item['price'];
        $item['stock'] = (int) $item['stock'];

        $cart[] = $item;
        $total += $item['price'] * $item['quantity'];
    }

    $stmt->close();
} else {
    $message = 'Unable to load your cart. Please try again.';
    $messageType = 'danger';
}

/*
|--------------------------------------------------------------------------
| Handle checkout form
|--------------------------------------------------------------------------
*/

$name = '';
$email = '';
$phone = '';
$address = '';
$city = '';
$state = '';
$pincode = '';
$payment = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');
    $payment = $_POST['payment'] ?? '';

    $validState = in_array($state, $indianStates, true);
    $validCity = isset($locations[$state][$city]);
    $validPin = $validCity &&
        in_array($pincode, $locations[$state][$city], true);

    if (empty($cart)) {
        $message = 'Your cart is empty.';
        $messageType = 'warning';
    } elseif (
        $name === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !preg_match('/^[0-9]{10}$/', $phone) ||
        $address === '' ||
        !$validState ||
        !$validCity ||
        !$validPin ||
        !in_array($payment, $allowedPayments, true)
    ) {
        $message = 'Please enter valid details and select a valid delivery address.';
        $messageType = 'danger';
    } else {
        $_SESSION['checkout_details'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
            'payment' => $payment
        ];

        $message = 'Your checkout details have been saved. Online payment options require a payment gateway to complete payment.';
        $messageType = 'success';
    }
}

function checkout_h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$locationJson = json_encode(
    $locations,
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);
?>
<!DOCTYPE html>
<html lang="en">
<style>
    /* Additional inline styles can be added here if needed */
    :root {
        --pink: #e75480;
        --pink-dark: #d94370;
        --pink-soft: #fff0f4;
        --pink-light: #fbd3df;
        --text: #33252b;
        --muted: #7d7378;
        --border: #f0dce3;
        --white: #ffffff;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #fff8fa;
        color: var(--text);
        font-family: Arial, Helvetica, sans-serif;
    }

    .checkout-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 6%;
        background: var(--white);
        border-bottom: 1px solid var(--border);
    }

    .brand {
        color: var(--pink);
        font-size: 29px;
        font-weight: 700;
        text-decoration: none;
        letter-spacing: 1px;
    }

    .checkout-header span {
        color: var(--muted);
        font-size: 14px;
    }

    .checkout-container {
        max-width: 1150px;
        margin: 40px auto;
        padding-bottom: 35px;
    }

    .checkout-title {
        margin-bottom: 28px;
        text-align: center;
    }

    .checkout-title h2 {
        margin-bottom: 10px;
        font-size: 30px;
        font-weight: 700;
    }

    .checkout-title p {
        margin: 0;
        color: var(--muted);
        font-size: 15px;
    }

    .checkout-card {
        margin-bottom: 24px;
        padding: 25px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(130, 58, 83, 0.05);
    }

    .checkout-card h4 {
        margin-bottom: 22px;
        padding-bottom: 13px;
        border-bottom: 1px solid var(--border);
        font-size: 20px;
        font-weight: 700;
    }

    .checkout-card label:not(.payment-option) {
        display: block;
        margin: 15px 0 8px;
        font-size: 14px;
        font-weight: 600;
    }

    .checkout-card .form-control,
    .checkout-card .form-select {
        width: 100%;
        min-height: 45px;
        padding: 11px 13px;
        color: var(--text);
        background-color: var(--white);
        border: 1px solid #e8d6dd;
        border-radius: 9px;
        font-size: 14px;
    }

    .checkout-card textarea.form-control {
        min-height: 90px;
        resize: vertical;
    }

    .checkout-card .form-control:focus,
    .checkout-card .form-select:focus {
        border-color: var(--pink);
        box-shadow: 0 0 0 3px rgba(231, 84, 128, 0.12);
        outline: none;
    }

    .checkout-card .form-select:disabled {
        color: #999;
        background-color: #f8f1f4;
        cursor: not-allowed;
    }

    .checkout-card .text-muted {
        display: block;
        margin-top: 10px;
        font-size: 12px;
        line-height: 1.5;
    }

    .payment-option {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 15px;
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: #fffafb;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.2s, border-color 0.2s;
    }

    .payment-option:hover {
        background: var(--pink-soft);
        border-color: var(--pink-light);
    }

    .payment-option input {
        width: 16px;
        height: 16px;
        margin: 0;
        accent-color: var(--pink);
        flex-shrink: 0;
    }

    .payment-note {
        display: block;
        margin-top: 18px;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .order-summary {
        position: sticky;
        top: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        margin: 16px 0;
        font-size: 14px;
        line-height: 1.5;
    }

    .summary-item span:first-child {
        overflow-wrap: anywhere;
    }

    .summary-item span:last-child {
        flex-shrink: 0;
        text-align: right;
    }

    .order-summary hr {
        border-color: var(--border);
        opacity: 1;
    }

    .grand-total {
        margin: 20px 0;
        font-size: 19px;
        font-weight: 700;
    }

    .grand-total span:last-child {
        color: var(--pink-dark);
    }

    .place-order-btn {
        width: 100%;
        margin-top: 12px;
        padding: 14px 18px;
        color: var(--white);
        background: var(--pink);
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, transform 0.2s;
    }

    .place-order-btn:hover {
        background: var(--pink-dark);
        transform: translateY(-1px);
    }

    .back-to-cart {
        display: block;
        margin-top: 18px;
        color: var(--pink-dark);
        text-align: center;
        text-decoration: none;
        font-size: 14px;
    }

    .back-to-cart:hover {
        text-decoration: underline;
    }

    .alert {
        border-radius: 10px;
    }

    @media (max-width: 991px) {
        .checkout-container {
            margin-top: 28px;
        }

        .order-summary {
            position: static;
        }
    }

    @media (max-width: 576px) {
        .checkout-header {
            padding: 15px 5%;
        }

        .brand {
            font-size: 24px;
        }

        .checkout-header span {
            font-size: 12px;
        }

        .checkout-container {
            margin: 25px auto;
            padding-right: 18px;
            padding-left: 18px;
        }

        .checkout-title h2 {
            font-size: 25px;
        }

        .checkout-card {
            padding: 18px;
            border-radius: 12px;
        }

        .checkout-card h4 {
            font-size: 18px;
        }
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darlings | Checkout</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="css/checkout.css">
</head>

<body>

    <header class="checkout-header">
        <a href="index.php" class="brand">Darlings</a>
        <span>Secure Checkout</span>
    </header>

    <div class="container checkout-container">

        <div class="checkout-title">
            <h2>Complete Your Order</h2>
            <p>Just a few details before your new favourites arrive.</p>
        </div>

        <?php if ($message !== ''): ?>
            <div class="alert alert-<?= checkout_h($messageType) ?>">
                <?= checkout_h($message) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart)): ?>

            <div class="alert alert-warning">
                Your cart is empty.
                <a href="products.php">Continue shopping</a>
            </div>
        <?php else: ?>
            <form action="place_order.php" method="POST">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="checkout-card">
                            <h4>Customer Details</h4>
                            <label for="name">Full Name</label>
                            <input
                                type="text" id="name" name="name"
                                class="form-control" required
                                autocomplete="name"
                                value="<?= checkout_h($name) ?>">
                            <label for="email">Email Address</label>
                            <input
                                type="email" id="email" name="email"
                                class="form-control" required
                                autocomplete="email"
                                value="<?= checkout_h($email) ?>">
                            <label for="phone">Phone Number</label>
                            <input
                                type="tel" id="phone" name="phone"
                                class="form-control" pattern="[0-9]{10}"
                                maxlength="10" inputmode="numeric" required
                                autocomplete="tel"
                                value="<?= checkout_h($phone) ?>">
                        </div>
                        <div class="checkout-card">
                            <h4>Delivery Address</h4>
                            <label for="address">House No. / Street Address</label>
                            <textarea
                                id="address" name="address"
                                class="form-control" rows="3" required
                                autocomplete="street-address"><?= checkout_h($address) ?></textarea>

                            <label for="state">State / Union Territory</label>
                            <select
                                id="state" name="state"
                                class="form-select" required>
                                <option value="">Select State / UT</option>
                                <?php foreach ($indianStates as $stateOption): ?>
                                    <option
                                        value="<?= checkout_h($stateOption) ?>"
                                        <?= $state === $stateOption ? 'selected' : '' ?>>
                                        <?= checkout_h($stateOption) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="city">City</label>
                            <select
                                id="city" name="city"
                                class="form-select" required
                                <?= $state === '' ? 'disabled' : '' ?>>
                                <option value="">Select City</option>
                            </select>

                            <label for="pincode">PIN Code</label>
                            <select
                                id="pincode" name="pincode"
                                class="form-select" required
                                <?= $city === '' ? 'disabled' : '' ?>>
                                <option value="">Select PIN Code</option>
                            </select>

                            <small class="text-muted">
                                Only locations included in the current dataset
                                are available for selection.
                            </small>
                        </div>

                        <div class="checkout-card">
                            <h4>Payment Method</h4>

                            <?php
                            $paymentLabels = [
                                'cod' => 'Cash on Delivery',
                                'google_pay' => 'Google Pay (UPI)',
                                'phonepe' => 'PhonePe (UPI)',
                                'paytm' => 'Paytm (UPI)',
                                'bhim_upi' => 'BHIM UPI',
                                'card' => 'Credit / Debit Card',
                                'gift_card' => 'Darlings Gift Card',
                                'emi' => 'EMI'
                            ];
                            ?>
                            <?php foreach ($paymentLabels as $paymentValue => $paymentLabel): ?>
                                <label class="payment-option">
                                    <input
                                        type="radio" name="payment"
                                        value="<?= checkout_h($paymentValue) ?>"
                                        <?= $payment === $paymentValue ? 'checked' : '' ?>
                                        required>
                                    <?= checkout_h($paymentLabel) ?>
                                </label>
                            <?php endforeach; ?>
                            <small class="payment-note">
                                Online payments, gift cards, and EMI require
                                the appropriate payment provider or gateway.
                                Card details are not collected on this page.
                            </small>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="checkout-card order-summary">
                            <h4>Order Summary</h4>
                            <?php foreach ($cart as $item): ?>
                                <div class="summary-item">
                                    <span>
                                        <?= checkout_h($item['name']) ?>
                                        (<?= checkout_h($item['size']) ?>)
                                        × <?= $item['quantity'] ?>
                                    </span>
                                    <span>
                                        ₹<?= number_format(
                                                $item['price'] * $item['quantity'],
                                                2
                                            ) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                            <hr>
                            <div class="summary-item">
                                <span>Subtotal</span>
                                <span>₹<?= number_format($total, 2) ?></span>
                            </div>
                            <div class="summary-item">
                                <span>Delivery</span>
                                <span>Free</span>
                            </div>
                            <hr>
                            <div class="summary-item grand-total">
                                <span>Total</span>
                                <span>₹<?= number_format($total, 2) ?></span>
                            </div>
                            <button type="submit" class="place-order-btn">
                                Place Order
                            </button>
                            <a href="cart.php" class="back-to-cart">
                                &larr; Return to Cart
                            </a>
                        </div>
                    </div>

                </div>
            </form>

        <?php endif; ?>

    </div>

    <script>
        const locations = <?= $locationJson ?: '{}' ?>;

        const stateSelect = document.getElementById('state');
        const citySelect = document.getElementById('city');
        const pinSelect = document.getElementById('pincode');

        function fillDropdown(select, values, placeholder) {
            select.innerHTML = '';
            select.add(new Option(placeholder, ''));

            values.forEach(value => {
                select.add(new Option(value, value));
            });

            select.disabled = values.length === 0;
        }

        function updateCities(selectedCity = '') {
            const cities = Object.keys(locations[stateSelect.value] || {}).sort();

            fillDropdown(citySelect, cities, 'Select City');
            fillDropdown(pinSelect, [], 'Select City First');

            if (cities.includes(selectedCity)) {
                citySelect.value = selectedCity;
                updatePins(<?= json_encode($pincode, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>);
            }
        }

        function updatePins(selectedPin = '') {
            const pins = locations[stateSelect.value]?.[citySelect.value] || [];

            fillDropdown(pinSelect, pins, 'Select PIN Code');

            if (pins.includes(selectedPin)) {
                pinSelect.value = selectedPin;
            }
        }

        stateSelect.addEventListener('change', function() {
            updateCities();
        });

        citySelect.addEventListener('change', function() {
            updatePins();
        });

        updateCities(<?= json_encode($city, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>);
    </script>

</body>

</html>