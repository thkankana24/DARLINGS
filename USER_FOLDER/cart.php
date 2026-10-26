<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/sql/config.php';
require_once __DIR__ . '/sql/customer_session.php';

$sessionId = session_id();
$message = '';

// Folder where product images are uploaded (same path used in api/products.php)
$imageBase = '/darlings/ADMIN_FOLDER/upload/';

/* ---------- Remove one item ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['remove_cart_item'])) {

    $cartId = filter_input(INPUT_POST, 'cart_id', FILTER_VALIDATE_INT);

    if ($cartId) {
        $stmt = $conn->prepare(
            "DELETE FROM cart
             WHERE id = ? AND session_id = ?"
        );
        $stmt->bind_param('is', $cartId, $sessionId);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: cart.php');
    exit();
}

/* ---------- Clear the whole cart ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['clear_cart'])) {

    $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $stmt->close();

    header('Location: cart.php');
    exit();
}

/* ---------- Load cart items ---------- */
$sql = "
    SELECT
        c.id AS cart_id,
        c.product_id,
        c.quantity,
        c.size,
        p.product_name AS name,
        p.product_image AS image,
        p.price AS price
    FROM cart AS c
    INNER JOIN products_admin AS p
        ON p.id = c.product_id
    WHERE c.session_id = ?
    ORDER BY c.added_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $sessionId);
$stmt->execute();
$result = $stmt->get_result();

$cart = [];
$total = 0;
$itemCount = 0;

while ($row = $result->fetch_assoc()) {
    $row['price'] = (float) $row['price'];
    $row['quantity'] = (int) $row['quantity'];
    $row['line_total'] = $row['price'] * $row['quantity'];

    $total += $row['line_total'];
    $itemCount += $row['quantity'];

    $cart[] = $row;
}

$stmt->close();

$_SESSION['cart'] = [];
foreach ($cart as $item) {
    $_SESSION['cart'][] = [
        'id'       => $item['product_id'],
        'name'     => $item['name'],
        'image'    => $item['image'],
        'price'    => $item['price'],
        'quantity' => $item['quantity'],
        'size'     => $item['size']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darlings | Your Cart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:ital,wght@1,600&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/cart-page.css">
</head>

<body>

<!-- HEADER -->
<header class="cart-header">
    <a href="products.php" class="back">&larr; Continue shopping</a>
    <a class="cart-brand" href="index.php">Darlings</a>
    <a href="orders.php">My orders</a>
</header>

<!-- MAIN CART -->
<main class="cart-main">

    <?php if (!empty($_SESSION['cart_message'])): ?>
        <p class="cart-message">
            <?= htmlspecialchars($_SESSION['cart_message'], ENT_QUOTES, 'UTF-8') ?>
        </p>
        <?php unset($_SESSION['cart_message']); ?>
    <?php endif; ?>

    <section class="cart-heading">
        <p>YOUR BAG</p>
        <h1>
            Shopping cart
            <span id="itemCount">(<?= $itemCount ?> items)</span>
        </h1>
        <span>Everything you love, in one place.</span>
    </section>

    <div class="cart-layout">

        <!-- CART PRODUCTS -->
        <section id="cartItems" class="cart-items-panel" aria-label="Shopping cart items">

            <?php if (!empty($cart)): ?>

                <?php foreach ($cart as $item): ?>

                    <article class="cart-item">

                        <!-- PRODUCT IMAGE -->
                        <div class="cart-product-image">
                            <?php
                            $image = trim((string) ($item['image'] ?? ''));
                            $imageUrl = $image !== ''
                                ? $imageBase . rawurlencode(basename(str_replace('\\', '/', $image)))
                                : '';
                            ?>

                            <?php if ($imageUrl !== ''): ?>

                                <img
                                    src="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>"
                                    onerror="this.style.display='none';
                                    this.nextElementSibling.style.display='flex';"
                                >

                                <div class="no-product-image" style="display:none;">
                                    Image unavailable
                                </div>

                            <?php else: ?>

                                <div class="no-product-image">
                                    No image
                                </div>

                            <?php endif; ?>
                        </div>

                        <!-- PRODUCT INFO -->
                        <div class="item-info">
                            <strong>
                                <?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>
                            </strong>

                            <p>
                                Size:
                                <?= htmlspecialchars($item['size'] ?: 'One size', ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <p>
                                ₹<?= number_format($item['price'], 2) ?>
                                × <?= $item['quantity'] ?>
                            </p>

                            <strong>
                                ₹<?= number_format($item['line_total'], 2) ?>
                            </strong>
                        </div>

                        <!-- REMOVE BUTTON -->
                        <div class="item-actions">
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="cart_id" value="<?= (int) $item['cart_id'] ?>">
                                <button
                                    type="submit"
                                    name="remove_cart_item"
                                    value="1"
                                    class="remove-item">
                                    Remove
                                </button>
                            </form>
                        </div>

                    </article>

                <?php endforeach; ?>

                <form method="POST" action="cart.php" class="clear-cart-form">
                    <button
                        type="submit"
                        name="clear_cart"
                        value="1"
                        class="clear-cart-button"
                        onclick="return confirm('Remove all items from your cart?');">
                        Clear cart
                    </button>
                </form>

            <?php else: ?>

                <p>Your cart is empty. Add something you love!</p>

            <?php endif; ?>

        </section>

        <!-- ORDER SUMMARY -->
        <aside class="order-summary">
            <h2>Order summary</h2>

            <div class="summary-row">
                <span>Subtotal</span>
                <strong>₹<?= number_format($total, 2) ?></strong>
            </div>

            <div class="summary-row">
                <span>Delivery</span>
                <strong><?= $itemCount > 0 ? 'Free' : '—' ?></strong>
            </div>

            <div class="summary-row total">
                <span>Total</span>
                <strong>₹<?= number_format($total, 2) ?></strong>
            </div>

            <p class="delivery-note">
                <?= $itemCount > 0
                    ? 'You qualify for free delivery.'
                    : 'Add something you love to get started.' ?>
            </p>

            <?php if ($itemCount > 0): ?>
                <a href="checkout.php" class="checkout-btn checkout-button">
                    Proceed to Checkout
                </a>
            <?php else: ?>
                <button type="button" class="checkout-button" disabled>
                    Proceed to checkout
                </button>
            <?php endif; ?>

            <a href="products.php" class="continue-shopping">
                Continue shopping
            </a>
        </aside>

    </div>
</main>

<!-- FOOTER -->
<footer class="darlings-footer">
    <div class="footer-container">
        <div>
            <h3>Darlings</h3>
            <p>Your one-stop shop for fashion, shoes, and more.</p>
        </div>
        <div>
            <h4>Quick Links</h4>
            <ul>
                <li><a href="products.php">Shop</a></li>
                <li><a href="orders.php">My Orders</a></li>
                <li><a href="index.php">Home</a></li>
            </ul>
        </div>
        <div>
            <h4>Customer Service</h4>
            <ul>
                <li><a href="cart.php">Your Cart</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="FAQ.php">FAQs</a></li>
                <li><a href="shipping&returns.php">Shipping &amp; Returns</a></li>
                <li><a href="privacy_policy.php">Privacy Policy</a></li>
                <li><a href="terms&conditions.php">Terms &amp; Conditions</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 Darlings. All rights reserved.</p>
    </div>
</footer>

</body>
</html>