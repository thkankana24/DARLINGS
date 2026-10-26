<?php
require_once __DIR__ . '/sql/customer_session.php';   // starts session, redirects to login if not logged in
require_once __DIR__ . '/sql/config.php';             // gives $conn

$imageBase = '/darlings/ADMIN_FOLDER/upload/';        // same image folder used in api/products.php
$customerEmail = strtolower(trim($_SESSION['customer']['email'] ?? ''));

/* ---------- helpers ---------- */
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Indian number format: ₹1,23,456
function inr($amount): string {
    $n = (string)(int)round((float)$amount);
    if (strlen($n) > 3) {
        $last = substr($n, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', substr($n, 0, -3));
        $n = $rest . ',' . $last;
    }
    return '₹' . $n;
}

/* ---------- load this customer's orders and their items ---------- */
$orders = [];

$sql = "SELECT o.id, o.order_number, o.shipping_address, o.total_amount, o.status,
               o.payment_method, o.payment_status, o.order_date,
               i.item_name, i.image_path, i.quantity, i.unit_price
        FROM orders o
        LEFT JOIN order_items i ON i.order_id = o.id
        WHERE LOWER(o.customer_email) = ?
        ORDER BY o.order_date DESC, o.id DESC, i.id ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $customerEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['id'];

    if (!isset($orders[$id])) {
        $orders[$id] = [
            'number'  => $row['order_number'],
            'address' => $row['shipping_address'],
            'total'   => $row['total_amount'],
            'status'  => $row['status'] ?: 'Pending',
            'payment' => trim($row['payment_method'] . ' · ' . $row['payment_status'], ' ·'),
            'date'    => $row['order_date'],
            'items'   => [],
        ];
    }

    if ($row['item_name'] !== null) {
        $orders[$id]['items'][] = [
            'name'  => $row['item_name'],
            'image' => $row['image_path'],
            'qty'   => (int)$row['quantity'],
            'price' => (float)$row['unit_price'],
        ];
    }
}
mysqli_stmt_close($stmt);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Darlings | Your orders</title>
  <link rel="stylesheet" href="css/order.css">
  <link rel="stylesheet" href="css/footer.css">
  <style>
    /* status colours (works with your existing .status class) */
    .status-pending{background:#fff4e0;color:#b36b00}
    .status-processing,.status-shipped{background:#e8f1ff;color:#1f5fbf}
    .status-delivered{background:#e6f6ec;color:#23804a}
    .status-cancelled{background:#fde8ec;color:#c93c69}
    .order-foot small,.order-address{display:block;margin-top:4px}
  </style>
</head>
<body>
  <header class="orders-header">
    <a href="products.php">&larr; Continue shopping</a>
    <a class="orders-brand" href="index.php">Darlings</a>
    <a href="userpage.php">Account</a>
  </header>

  <main class="orders-wrap">
    <div class="orders-heading">
      <p class="eyebrow">YOUR ACCOUNT</p>
      <h1>Your orders</h1>
      <p>Everything you have ordered from Darlings, in one place.</p>
    </div>

    <section id="ordersList" aria-live="polite">
      <?php if (empty($orders)): ?>

        <div class="empty-orders">
          <h2>No orders yet</h2>
          <p>Your placed orders will appear here.</p>
          <a href="products.php">Browse the collection</a>
        </div>

      <?php else: ?>
        <?php foreach ($orders as $order): ?>
          <article class="order-card">
            <div class="order-top">
              <div>
                <span class="status status-<?= e(strtolower($order['status'])) ?>"><?= e($order['status']) ?></span>
                <h2>Order <?= e($order['number']) ?></h2>
                <p>Placed on <?= e(date('j M Y', strtotime($order['date']))) ?></p>
              </div>
              <strong class="order-total"><?= e(inr($order['total'])) ?></strong>
            </div>

            <ul>
              <?php foreach ($order['items'] as $item): ?>
                <li>
                  <img src="<?= e($imageBase . rawurlencode(basename((string)$item['image']))) ?>"
                       alt="<?= e($item['name']) ?>"
                       onerror="this.src='img/logo.jpeg'">
                  <span>
                    <?= e($item['name']) ?>
                    <small>Qty: <?= (int)$item['qty'] ?> &middot; <?= e(inr($item['price'])) ?> each</small>
                  </span>
                  <strong><?= e(inr($item['price'] * $item['qty'])) ?></strong>
                </li>
              <?php endforeach; ?>
            </ul>

            <div class="order-foot">
              <?= e($order['payment'] ?: 'Payment pending') ?>
              <span class="order-address">Delivering to: <?= e($order['address']) ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </main>

  <footer class="darlings-footer">
    <div class="footer-container">
      <div>
        <h3>Darlings</h3>
        <p>Your one-stop shop for fashion, shoes, and more.</p>
      </div>
      <div>
        <h4>Quick Links</h4>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="products.php">Shop</a></li>
          <li><a href="about.php">About Us</a></li>
        </ul>
      </div>
      <div>
        <h4>Customer Service</h4>
        <ul>
          <li><a href="FAQ.php">FAQs</a></li>
          <li><a href="shipping&returns.php">Shipping &amp; Returns</a></li>
          <li><a href="privacy_policy.php">Privacy Policy</a></li>
          <li><a href="term&conditions.php">Terms &amp; Conditions</a></li>
        </ul>
      </div>
      <div>
        <h4>Subscribe</h4>
        <form class="newsletter" onsubmit="return false">
          <input type="email" placeholder="Enter your email" aria-label="Email address">
          <button>Subscribe</button>
        </form>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2026 Darlings. All rights reserved.</p>
      <div class="socials"><a href="#">Facebook</a><a href="#">Instagram</a></div>
    </div>
  </footer>
</body>
</html>