<?php
include_once 'PAGES/auth_check.php';
require_once 'sql/config.php';

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Search and filters
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$allowedStatuses = ['Pending', 'Shipped', 'Delivered', 'Cancelled'];

if (!in_array($status, $allowedStatuses, true)) {
    $status = '';
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
    $from = '';
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
    $to = '';
}

// Summary cards
$summary = [
    'total' => 0,
    'Delivered' => 0,
    'Pending' => 0,
    'Cancelled' => 0
];

$summaryResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total,
        COALESCE(SUM(status = 'Delivered'), 0) AS delivered,
        COALESCE(SUM(status = 'Pending'), 0) AS pending,
        COALESCE(SUM(status = 'Cancelled'), 0) AS cancelled
     FROM orders"
);

if ($summaryResult) {
    $s = mysqli_fetch_assoc($summaryResult);
    $summary['total'] = (int)$s['total'];
    $summary['Delivered'] = (int)$s['delivered'];
    $summary['Pending'] = (int)$s['pending'];
    $summary['Cancelled'] = (int)$s['cancelled'];
}

// Build the filtered order query
$where = [];

if ($search !== '') {
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $where[] = "(o.order_number LIKE '%$safeSearch%'
        OR o.customer_name LIKE '%$safeSearch%'
        OR o.customer_email LIKE '%$safeSearch%')";
}

if ($status !== '') {
    $safeStatus = mysqli_real_escape_string($conn, $status);
    $where[] = "o.status = '$safeStatus'";
}

if ($from !== '') {
    $safeFrom = mysqli_real_escape_string($conn, $from);
    $where[] = "DATE(o.order_date) >= '$safeFrom'";
}

if ($to !== '') {
    $safeTo = mysqli_real_escape_string($conn, $to);
    $where[] = "DATE(o.order_date) <= '$safeTo'";
}

$whereSQL = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "
    SELECT
        o.id,
        o.order_number,
        o.customer_name,
        o.customer_email,
        o.total_amount,
        o.status,
        o.order_date,
        COUNT(oi.id) AS item_count,
        COALESCE(SUM(oi.quantity), 0) AS total_quantity,
        GROUP_CONCAT(
            CONCAT(oi.item_name, ' x', oi.quantity)
            ORDER BY oi.id SEPARATOR ', '
        ) AS items,
        SUBSTRING_INDEX(
            GROUP_CONCAT(
                oi.image_path ORDER BY oi.id SEPARATOR '|||'
            ), '|||', 1
        ) AS image_path
    FROM orders o
    LEFT JOIN order_items oi ON oi.order_id = o.id
    $whereSQL
    GROUP BY o.id
    ORDER BY o.order_date DESC, o.id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Unable to load orders. Please check the orders database tables.');
}

// Match the main layout of your Darlings admin panel
include 'PAGES/header.php';
include 'PAGES/sidebar.php';
include 'PAGES/top-header.php';
?>

<link rel="stylesheet" href="css_file/orders.css">

<main class="orders-page">

    <div class="orders-heading">
        <div class="orders-heading-icon">🛒</div>
        <div>
            <h1>Orders</h1>
            <p>Manage and track all customer orders from your store.</p>
        </div>
    </div>

    <section class="orders-stats">

        <div class="stat-card stat-total">
            <div class="stat-icon">📋</div>
            <div>
                <p>Total Orders</p>
                <h2><?= $summary['total'] ?></h2>
                <span>All customer orders</span>
            </div>
        </div>

        <div class="stat-card stat-delivered">
            <div class="stat-icon">🚚</div>
            <div>
                <p>Delivered</p>
                <h2><?= $summary['Delivered'] ?></h2>
                <span>Successfully delivered</span>
            </div>
        </div>

        <div class="stat-card stat-pending">
            <div class="stat-icon">◷</div>
            <div>
                <p>Pending</p>
                <h2><?= $summary['Pending'] ?></h2>
                <span>Awaiting processing</span>
            </div>
        </div>

        <div class="stat-card stat-cancelled">
            <div class="stat-icon">✕</div>
            <div>
                <p>Cancelled</p>
                <h2><?= $summary['Cancelled'] ?></h2>
                <span>Cancelled orders</span>
            </div>
        </div>

    </section>

    <section class="orders-panel">

        <form method="GET" action="orders.php" class="orders-filters">

            <select name="status" aria-label="Filter by status">
                <option value="">All Statuses</option>
                <?php foreach ($allowedStatuses as $option): ?>
                    <option value="<?= e($option) ?>"
                        <?= $status === $option ? 'selected' : '' ?>>
                        <?= e($option) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="date-filter">
                <input type="date" name="from"
                    value="<?= e($from) ?>" aria-label="From date">
                <span>to</span>
                <input type="date" name="to"
                    value="<?= e($to) ?>" aria-label="To date">
            </div>

            <div class="orders-search">
                <span>⌕</span>
                <input type="search" name="search"
                    value="<?= e($search) ?>"
                    placeholder="Search order ID or customer...">
            </div>

            <button type="submit" class="filter-button">Search</button>
            <a href="orders.php" class="reset-button">Reset</a>

        </form>

        <div class="orders-table-wrap">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $number = 1; ?>
                    <?php while ($order = mysqli_fetch_assoc($result)): ?>

                        <tr>
                            <td><?= $number++ ?></td>

                            <td class="order-number">
                                #<?= e($order['order_number']) ?>
                            </td>

                            <td>
                                <div class="customer-name">
                                    <?= e($order['customer_name']) ?>
                                </div>
                                <div class="customer-email">
                                    <?= e($order['customer_email']) ?>
                                </div>
                            </td>

                            <td>
                                <div class="item-cell">
                                    <?php if (!empty($order['image_path'])): ?>
                                        <img
                                            src="<?= e($order['image_path']) ?>"
                                            alt="Product"
                                            class="order-product-image"
                                            onerror="this.style.display='none'">
                                    <?php else: ?>
                                        <div class="no-product-image">📦</div>
                                    <?php endif; ?>

                                    <div class="item-description">
                                        <?= e($order['items'] ?: 'No items') ?>
                                    </div>
                                </div>
                            </td>

                            <td class="order-amount">
                                ₹<?= number_format((float)$order['total_amount'], 2) ?>
                            </td>

                            <td>
                                <span class="status-badge status-<?= strtolower(e($order['status'])) ?>">
                                    <?= e($order['status']) ?>
                                </span>
                            </td>

                            <td>
                                <div>
                                    <?= date('d M, Y', strtotime($order['order_date'])) ?>
                                </div>
                                <div class="order-time">
                                    <?= date('h:i A', strtotime($order['order_date'])) ?>
                                </div>
                            </td>

                            <td>
                                <a class="view-order-btn"
                                    href="order-details.php?id=<?= (int)$order['id'] ?>">
                                    <span>◎</span> View
                                </a>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty-orders">
                            <div>📦</div>
                            <strong>No orders found</strong>
                            <p>Orders will appear here when they are saved in your database.</p>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="orders-footer">
            <span>
                Showing <?= mysqli_num_rows($result) ?> matching order(s)
            </span>
            <span class="orders-footer-note">
                Data retrieved from your MySQL database
            </span>
        </div>

    </section>

</main>
</body>
</html>