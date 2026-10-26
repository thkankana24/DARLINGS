
<?php
include 'PAGES/auth_check.php';
require_once 'sql/config.php';

$customers = [];
$totalCustomers = 0;
$search = trim($_GET['search'] ?? '');
$error = '';

$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers");

if ($countResult) {
    $totalCustomers = (int) mysqli_fetch_assoc($countResult)['total'];
} else {
    $error = "Unable to load customer details.";
}

if ($search !== '') {
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, name, email, created_at
         FROM customers
         WHERE name LIKE ? OR email LIKE ?
         ORDER BY id DESC"
    );

    if ($stmt) {
        $keyword = "%{$search}%";
        mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $customers[] = $row;
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = "Unable to search customers.";
    }
} else {
    $result = mysqli_query(
        $conn,
        "SELECT id, name, email, created_at
         FROM customers
         ORDER BY id DESC"
    );

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $customers[] = $row;
        }
    } else {
        $error = "Unable to load customer details.";
    }
}

include 'PAGES/header.php';
include 'PAGES/sidebar.php';
include 'PAGES/top-header.php';
?>

<style>
.customers-content {
    width: calc(100% - 30px);
    max-width: 1400px;
    margin: 25px auto;
    padding: 0 10px 35px;
    box-sizing: border-box;
    color: #283044;
}

.customers-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.customers-heading h1 {
    margin: 0 0 8px;
    font-size: 28px;
    font-weight: 700;
}

.customers-heading p {
    margin: 0;
    color: #85899b;
    font-size: 14px;
}

.back-link {
    padding: 11px 16px;
    border-radius: 10px;
    background: #fff0f6;
    color: #d83f86;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
}

.back-link:hover {
    background: #ffe2ee;
    color: #ad2866;
}

.customer-summary {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 25px;
    padding: 22px;
    background: #fff0f6;
    border: 1px solid #f8dce8;
    border-radius: 16px;
}

.customer-summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: #fff;
    color: #d83f86;
    font-size: 25px;
}

.customer-summary p {
    margin: 0 0 5px;
    color: #72778b;
    font-size: 14px;
}

.customer-summary h2 {
    margin: 0;
    color: #283044;
    font-size: 26px;
}

.customers-panel {
    padding: 24px;
    background: #fff;
    border: 1px solid #f0e8ee;
    border-radius: 17px;
    box-shadow: 0 5px 22px rgba(49, 35, 55, 0.04);
}

.customers-panel-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 22px;
}

.customers-panel-heading h2 {
    margin: 0 0 7px;
    font-size: 21px;
}

.customers-panel-heading p {
    margin: 0;
    color: #85899b;
    font-size: 14px;
}

.customer-search {
    display: flex;
    gap: 8px;
    max-width: 100%;
}

.customer-search input {
    width: 260px;
    max-width: 100%;
    padding: 11px 13px;
    border: 1px solid #eadde5;
    border-radius: 9px;
    outline: none;
    font-size: 14px;
    box-sizing: border-box;
}

.customer-search input:focus {
    border-color: #d83f86;
    box-shadow: 0 0 0 3px rgba(216, 63, 134, 0.08);
}

.customer-search button {
    padding: 11px 16px;
    border: none;
    border-radius: 9px;
    background: #d83f86;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.customer-search button:hover {
    background: #ad2866;
}

.customer-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.customer-table {
    width: 100%;
    min-width: 650px;
    border-collapse: collapse;
    text-align: left;
}

.customer-table th {
    padding: 14px 12px;
    background: #fff6fa;
    color: #73778a;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.customer-table td {
    padding: 16px 12px;
    border-bottom: 1px solid #f1edf1;
    color: #44495b;
    font-size: 14px;
}

.customer-table tbody tr:hover {
    background: #fffafd;
}

.customer-table tbody tr:last-child td {
    border-bottom: none;
}

.customer-id {
    color: #d83f86;
    font-weight: 700;
}

.customer-name {
    color: #283044;
    font-weight: 600;
}

.customer-email {
    overflow-wrap: anywhere;
}

.customer-date {
    white-space: nowrap;
}

.no-customers {
    padding: 30px !important;
    text-align: center;
    color: #85899b !important;
}

.customer-alert {
    margin-bottom: 20px;
    padding: 14px;
    border-radius: 10px;
    background: #fff0f0;
    color: #b42318;
    font-size: 14px;
}

@media (max-width: 700px) {
    .customers-content {
        width: 100%;
        margin: 18px auto;
        padding: 0 12px 25px;
    }

    .customers-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .customers-heading h1 {
        font-size: 24px;
    }

    .customers-panel {
        padding: 15px;
    }

    .customers-panel-heading {
        align-items: stretch;
        flex-direction: column;
    }

    .customer-search {
        width: 100%;
    }

    .customer-search input {
        width: 100%;
        min-width: 0;
    }

    .customer-summary {
        padding: 17px;
    }
}
</style>

<div class="customers-content">

    <div class="customers-heading">
        <div>
            <h1>Customers</h1>
            <p>View registered customers in your Darlings store.</p>
        </div>

        <a href="dashboard.php" class="back-link">
            ← Back to Dashboard
        </a>
    </div>

    <div class="customer-summary">
        <div class="customer-summary-icon">👥</div>
        <div>
            <p>Total Registered Customers</p>
            <h2><?php echo number_format($totalCustomers); ?></h2>
        </div>
    </div>

    <div class="customers-panel">

        <div class="customers-panel-heading">
            <div>
                <h2>Customer Details</h2>
                <p>Customer registration information</p>
            </div>

            <form method="GET" action="customers.php" class="customer-search">
                <input
                    type="search"
                    name="search"
                    placeholder="Search name or email..."
                    value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
                >
                <button type="submit">Search</button>
            </form>
        </div>

        <?php if ($error !== '') { ?>
            <div class="customer-alert">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } else { ?>

            <div class="customer-table-wrap">
                <table class="customer-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Email Address</th>
                            <th>Registration Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($customers)) { ?>
                            <?php foreach ($customers as $customer) { ?>
                                <tr>
                                    <td class="customer-id">
                                        #<?php echo (int) $customer['id']; ?>
                                    </td>

                                    <td class="customer-name">
                                        <?php echo htmlspecialchars($customer['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td class="customer-email">
                                        <?php echo htmlspecialchars($customer['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td class="customer-date">
                                        <?php
                                        echo !empty($customer['created_at'])
                                            ? date('d M Y, h:i A', strtotime($customer['created_at']))
                                            : '-';
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" class="no-customers">
                                    <?php echo $search !== ''
                                        ? 'No customers match your search.'
                                        : 'No customers have registered yet.'; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        <?php } ?>

    </div>
</div>

<?php include 'PAGES/footer.php'; ?>
