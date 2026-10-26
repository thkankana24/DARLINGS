<?php
session_start();

require_once __DIR__ . '/sql/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit();
}

// Read the existing checkout form field names
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$pincode = trim($_POST['pincode'] ?? '');
$payment = $_POST['payment'] ?? '';

// Validate customer information
if (
    $name === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^[0-9]{10}$/', $phone) ||
    $address === '' ||
    $city === '' ||
    !preg_match('/^[0-9]{6}$/', $pincode) ||
    !in_array($payment, ['cod', 'upi', 'card'], true)
) {
    exit("Invalid checkout details. Please return to checkout and try again.");
}

// Convert existing payment values to readable labels
$paymentMethods = [
    'cod'  => 'Cash on Delivery',
    'upi'  => 'UPI / QR Code',
    'card' => 'Credit / Debit Card'
];

$paymentMethod = $paymentMethods[$payment];

// Don't mark online payments as paid without gateway verification
$paymentStatus = 'Pending';
$orderStatus = 'Pending';

$sessionId = session_id();

// Get cart items and their current database prices
$sql = "SELECT
            c.product_id,
            c.quantity,
            c.size,
            p.product_name,
            p.price,
            p.product_image
        FROM cart c
        INNER JOIN products_admin p
            ON p.id = c.product_id
        WHERE c.session_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit("Could not prepare the cart query.");
}

mysqli_stmt_bind_param($stmt, "s", $sessionId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$items = [];
$total = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $quantity = (int)$row['quantity'];
    $price = (float)$row['price'];

    if ($quantity < 1 || $price < 0) {
        mysqli_stmt_close($stmt);
        exit("Invalid cart item. Please check your cart.");
    }

    $row['quantity'] = $quantity;
    $row['price'] = $price;

    $total += $quantity * $price;
    $items[] = $row;
}

mysqli_stmt_close($stmt);

if (empty($items)) {
    exit("Your cart is empty. Please add products before placing an order.");
}

$shippingAddress = $address . ', ' . $city . ' - ' . $pincode;
$orderNumber = 'DAR' . date('YmdHis') . random_int(100, 999);

// Save the order and its products together
mysqli_begin_transaction($conn);

try {
    // 1. Insert the main order
    $sql = "INSERT INTO orders (
                order_number,
                customer_name,
                customer_email,
                customer_phone,
                shipping_address,
                total_amount,
                status,
                payment_method,
                payment_status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        throw new Exception("Could not prepare order.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssssdsss",
        $orderNumber,
        $name,
        $email,
        $phone,
        $shippingAddress,
        $total,
        $orderStatus,
        $paymentMethod,
        $paymentStatus
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Could not save order.");
    }

    $orderId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // 2. Insert all products into order_items
    $sql = "INSERT INTO order_items (
                order_id,
                item_name,
                image_path,
                quantity,
                unit_price
            ) VALUES (?, ?, ?, ?, ?)";

    $itemStmt = mysqli_prepare($conn, $sql);

    if (!$itemStmt) {
        throw new Exception("Could not prepare order items.");
    }

    foreach ($items as $item) {
        $itemName = $item['product_name'];
        $imagePath = $item['product_image'] ?? '';
        $quantity = (int)$item['quantity'];
        $unitPrice = (float)$item['price'];

        mysqli_stmt_bind_param(
            $itemStmt,
            "issid",
            $orderId,
            $itemName,
            $imagePath,
            $quantity,
            $unitPrice
        );

        if (!mysqli_stmt_execute($itemStmt)) {
            throw new Exception("Could not save an order item.");
        }
    }

    mysqli_stmt_close($itemStmt);

    // 3. Remove cart items only after successful inserts
    $sql = "DELETE FROM cart WHERE session_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        throw new Exception("Could not prepare cart cleanup.");
    }

    mysqli_stmt_bind_param($stmt, "s", $sessionId);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Could not clear the cart.");
    }

    mysqli_stmt_close($stmt);

    // Commit all changes
    mysqli_commit($conn);

    // Clear saved checkout details
    unset($_SESSION['checkout_details']);

} catch (Throwable $e) {
    mysqli_rollback($conn);
    error_log("Darlings order error: " . $e->getMessage());

    http_response_code(500);
    exit("Your order could not be completed. Please try again.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Successful | Darlings</title>

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
            background: #f2eaff;
            font-family: Arial, sans-serif;
            color: #30243b;
        }

        .success-card {
            width: 100%;
            max-width: 460px;
            padding: 35px 25px;
            box-sizing: border-box;
            background: white;
            border-radius: 22px;
            text-align: center;
            box-shadow: 0 10px 35px #ddcce9;
        }

        .check {
            width: 72px;
            height: 72px;
            line-height: 72px;
            margin: auto;
            border-radius: 50%;
            background: #fce1ee;
            color: #d63384;
            font-size: 40px;
        }

        h1 {
            color: #d63384;
        }

        .order-number {
            padding: 12px;
            background: #fff2f8;
            border-radius: 10px;
            overflow-wrap: anywhere;
        }

        .btn {
            display: inline-block;
            margin-top: 18px;
            padding: 13px 24px;
            border-radius: 10px;
            background: #e94f91;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="success-card">
        <div class="check">&#10003;</div>

        <h1>Order Placed Successfully!</h1>
        <p>Thank you for shopping with Darlings.</p>

        <p>Your order number:</p>

        <div class="order-number">
            <strong>
                <?= htmlspecialchars($orderNumber) ?>
            </strong>
        </div>

        <h2>Total: ₹<?= number_format($total, 2) ?></h2>

        <p>
            Payment:
            <?= htmlspecialchars($paymentMethod) ?>
        </p>

        <p>
            Payment status: Pending
        </p>

        <a class="btn" href="cart.php">Continue Shopping</a>
    </div>
</body>
</html>