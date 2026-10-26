<?php
include_once 'PAGES/auth_check.php';
require_once 'sql/config.php';

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('Invalid order ID.');
}

$orderResult = mysqli_query(
    $conn,
    "SELECT * FROM orders WHERE id = $id LIMIT 1"
);

if (!$orderResult || mysqli_num_rows($orderResult) === 0) {
    die('Order not found.');
}

$order = mysqli_fetch_assoc($orderResult);

$itemsResult = mysqli_query(
    $conn,
    "SELECT * FROM order_items WHERE order_id = $id ORDER BY id"
);

if (!$itemsResult) {
    die('Unable to load order items.');
}

include 'PAGES/header.php';
include 'PAGES/sidebar.php';
include 'PAGES/top-header.php';
?>

<link rel="stylesheet" href="css_file/orders.css">

<main class="orders-page">

    <a href="orders.php" class="back-orders-link">
        ← Back to Orders
    </a>

    <div class="orders-heading details-heading">
        <div class="orders-heading-icon">📦</div>
        <div>
            <h1>Order Details</h1>
            <p>
                Order #<?= e($order['order_number']) ?>
                · Placed <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?>
            </p>
        </div>
    </div>

    <section class="details-card">

        <div class="details-card-heading">
            <h2>Customer Information</h2>
            <span class="status-badge status-<?= strtolower(e($order['status'])) ?>">
                <?= e($order['status']) ?>
            </span>
        </div>

        <div class="customer-details-grid">
            <div>
                <label>Customer Name</label>
                <p><?= e($order['customer_name']) ?></p>
            </div>

            <div>
                <label>Email Address</label>
                <p><?= e($order['customer_email']) ?></p>
            </div>

            <div>
                <label>Phone Number</label>
                <p><?= e($order['customer_phone'] ?: 'Not provided') ?></p>
            </div>

            <div>
                <label>Order Date</label>
                <p><?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
            </div>

            <div class="shipping-address">
                <label>Shipping Address</label>
                <p><?= nl2br(e($order['shipping_address'] ?: 'Not provided')) ?></p>
            </div>
        </div>

    </section>

    <section class="details-card">
        <h2>Ordered Products</h2>

        <div class="orders-table-wrap">
            <table class="orders-table details-items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($itemsResult) > 0): ?>
                        <?php while ($item = mysqli_fetch_assoc($itemsResult)): ?>
                            <tr>
                                <td>
                                    <div class="item-cell">
                                        <?php if (!empty($item['image_path'])): ?>
                                            <img
                                                class="order-product-image"
                                                src="<?= e($item['image_path']) ?>"
                                                alt="Product"
                                                onerror="this.style.display='none'">
                                        <?php else: ?>
                                            <div class="no-product-image">📦</div>
                                        <?php endif; ?>

                                        <span><?= e($item['item_name']) ?></span>
                                    </div>
                                </td>

                                <td><?= (int)$item['quantity'] ?></td>

                                <td>
                                    ₹<?= number_format((float)$item['unit_price'], 2) ?>
                                </td>

                                <td>
                                    ₹<?= number_format(
                                        (float)$item['unit_price'] * (int)$item['quantity'],
                                        2
                                    ) ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No product items are saved for this order.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="order-total-row">
            <span>Total Order Amount</span>
            <strong>
                ₹<?= number_format((float)$order['total_amount'], 2) ?>
            </strong>
        </div>
    </section>

</main>
</body>
</html>