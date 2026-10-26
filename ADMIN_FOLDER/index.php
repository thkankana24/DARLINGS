
<?php
include 'PAGES/auth_check.php';
require_once 'sql/config.php';

$totalOrders = 0;
$totalProducts = 0;
$totalCustomers = 0;
$totalSales = 0;
$recentOrders = [];

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
if ($result) {
    $totalOrders = (int) mysqli_fetch_assoc($result)['total'];
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products_admin");
if ($result) {
    $totalProducts = (int) mysqli_fetch_assoc($result)['total'];
}

$result = mysqli_query($conn, "SELECT COUNT(DISTINCT customer_email) AS total FROM orders WHERE customer_email IS NOT NULL AND customer_email != ''");
if ($result) {
    $totalCustomers = (int) mysqli_fetch_assoc($result)['total'];
}

$result = mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS sales FROM orders WHERE status IS NULL OR status != 'Cancelled'");
if ($result) {
    $totalSales = (float) mysqli_fetch_assoc($result)['sales'];
}

$result = mysqli_query($conn, "SELECT id, order_number, customer_name, total_amount, status, order_date FROM orders ORDER BY order_date DESC, id DESC LIMIT 5");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recentOrders[] = $row;
    }
}

include 'PAGES/header.php';
include 'PAGES/sidebar.php';
include 'PAGES/top-header.php';
?>

<style>
.dashboard-content {
    width: calc(100% - 30px);
    max-width: 1400px;
    margin: 25px auto;
    padding: 0 10px 35px;
    box-sizing: border-box;
    color: #283044;
}

.dashboard-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.dashboard-heading h1 {
    margin: 0 0 8px;
    font-size: 28px;
    font-weight: 700;
    color: #283044;
}

.dashboard-heading p,
.panel-heading p {
    margin: 0;
    color: #85899b;
    font-size: 14px;
}

.dashboard-date {
    background: #fff0f6;
    color: #d83f86;
    padding: 10px 15px;
    border-radius: 10px;
    font-weight: 600;
    white-space: nowrap;
}

.dashboard-boxes {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
    width: 100%;
    margin-bottom: 28px;
}

.dashboard-stat {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
    min-height: 135px;
    padding: 22px;
    border-radius: 17px;
    box-sizing: border-box;
    border: 1px solid rgba(40, 48, 68, 0.05);
}

.sales-stat {
    background: #fff0f6;
}

.orders-stat {
    background: #f1edff;
}

.customers-stat {
    background: #eaf8f1;
}

.products-stat {
    background: #fff3e8;
}

.stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.8);
    color: #d83f86;
    font-size: 25px;
    font-weight: 700;
}

.orders-stat .stat-icon {
    color: #7957d5;
}

.customers-stat .stat-icon {
    color: #279b70;
}

.products-stat .stat-icon {
    color: #e58a3a;
}

.dashboard-stat p {
    margin: 0 0 10px;
    color: #72778b;
    font-size: 14px;
}

.dashboard-stat h2 {
    margin: 0;
    color: #283044;
    font-size: 23px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.dashboard-panel,
.dashboard-quick-section {
    width: 100%;
    min-width: 0;
    margin-bottom: 28px;
    padding: 24px;
    background: #fff;
    border: 1px solid #f0e8ee;
    border-radius: 17px;
    box-shadow: 0 5px 22px rgba(49, 35, 55, 0.04);
    box-sizing: border-box;
}

.panel-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 22px;
}

.panel-heading h2 {
    margin: 0 0 7px;
    color: #283044;
    font-size: 21px;
    font-weight: 700;
}

.dashboard-link {
    color: #d83f86;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
}

.dashboard-link:hover {
    color: #ad2866;
}

.dashboard-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.dashboard-table {
    width: 100%;
    min-width: 580px;
    border-collapse: collapse;
    text-align: left;
}

.dashboard-table th {
    padding: 14px 12px;
    background: #fff6fa;
    color: #73778a;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.dashboard-table td {
    padding: 16px 12px;
    border-bottom: 1px solid #f1edf1;
    color: #44495b;
    font-size: 14px;
}

.dashboard-table tbody tr:last-child td {
    border-bottom: none;
}

.dashboard-table tbody tr:hover {
    background: #fffafd;
}

.order-status {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    background: #f3efff;
    color: #7654cb;
    font-size: 12px;
    font-weight: 600;
}

.no-orders {
    text-align: center;
    padding: 28px !important;
    color: #85899b !important;
}

.quick-access-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
}

.quick-access-card {
    display: block;
    min-width: 0;
    padding: 22px;
    border: 1px solid #f0e8ee;
    border-radius: 15px;
    background: #fffafd;
    text-decoration: none;
    box-sizing: border-box;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.quick-access-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(216, 63, 134, 0.10);
    border-color: #f4c6dc;
}

.quick-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    margin-bottom: 16px;
    border-radius: 14px;
    background: #ffe8f2;
    font-size: 24px;
}

.quick-access-card h3 {
    margin: 0 0 8px;
    color: #283044;
    font-size: 17px;
}

.quick-access-card p {
    margin: 0 0 17px;
    color: #85899b;
    font-size: 13px;
}

.quick-access-card span {
    color: #d83f86;
    font-size: 13px;
    font-weight: 600;
}

@media (max-width: 1100px) {
    .dashboard-boxes {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .quick-access-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 600px) {
    .dashboard-content {
        width: 100%;
        margin: 18px auto;
        padding: 0 12px 25px;
    }

    .dashboard-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .dashboard-heading h1 {
        font-size: 24px;
    }

    .dashboard-boxes,
    .quick-access-grid {
        grid-template-columns: 1fr;
        gap: 13px;
    }

    .dashboard-stat {
        min-height: 110px;
    }

    .dashboard-panel,
    .dashboard-quick-section {
        padding: 15px;
    }

    .panel-heading {
        align-items: flex-start;
    }
}
</style>

<div class="dashboard-content">

    <div class="dashboard-heading">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back! Here's what's happening in your Darlings store.</p>
        </div>
        <div class="dashboard-date">
            <?php echo date('d M Y'); ?>
        </div>
    </div>

    <div class="dashboard-boxes">
        <div class="dashboard-stat sales-stat">
            <div class="stat-icon">₹</div>
            <div>
                <p>Total Sales</p>
                <h2>₹<?php echo number_format($totalSales, 2); ?></h2>
            </div>
        </div>

        <div class="dashboard-stat orders-stat">
            <div class="stat-icon">▣</div>
            <div>
                <p>Total Orders</p>
                <h2><?php echo $totalOrders; ?></h2>
            </div>
        </div>

        <div class="dashboard-stat customers-stat">
            <div class="stat-icon">♟</div>
            <div>
                <p>Total Customers</p>
                <h2><?php echo $totalCustomers; ?></h2>
            </div>
        </div>

        <div class="dashboard-stat products-stat">
            <div class="stat-icon">□</div>
            <div>
                <p>Total Products</p>
                <h2><?php echo $totalProducts; ?></h2>
            </div>
        </div>
    </div>

    <div class="dashboard-panel">
        <div class="panel-heading">
            <div>
                <h2>Recent Orders</h2>
                <p>Latest orders received by your store</p>
            </div>
            <a href="orders.php" class="dashboard-link">View All →</a>
        </div>

        <div class="dashboard-table-wrap">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentOrders)) { ?>
                        <?php foreach ($recentOrders as $order) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>₹<?php echo number_format((float) $order['total_amount'], 2); ?></td>
                                <td>
                                    <span class="order-status">
                                        <?php echo htmlspecialchars($order['status'] ?? 'Pending', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    echo !empty($order['order_date'])
                                        ? date('d M Y', strtotime($order['order_date']))
                                        : '-';
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="no-orders">No orders found yet.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-quick-section">
        <div class="panel-heading">
            <div>
                <h2>Quick Access</h2>
                <p>Go directly to a section</p>
            </div>
        </div>

        <div class="quick-access-grid">
            <a href="product.php" class="quick-access-card">
                <div class="quick-icon">🛍️</div>
                <h3>View Products</h3>
                <p>Manage your store products</p>
                <span>Open section →</span>
            </a>

            <a href="orders.php" class="quick-access-card">
                <div class="quick-icon">📦</div>
                <h3>Manage Orders</h3>
                <p>Review customer orders</p>
                <span>Open section →</span>
            </a>

            <a href="customers.php" class="quick-access-card">
                <div class="quick-icon">👥</div>
                <h3>Manage Customers</h3>
                <p>View and manage customer information</p>
                <span>Open section →</span>
            </a>

            <a href="product.php" class="quick-access-card">
                <div class="quick-icon">📋</div>
                <h3>Manage Inventory</h3>
                <p>Check your product stock</p>
                <span>Open section →</span>
            </a>
        </div>
    </div>

</div>

<?php include 'PAGES/footer.php'; ?>
