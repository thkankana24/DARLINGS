<?php
$currentPage = basename($_SERVER['PHP_SELF']);

$dashboardStyle = ($currentPage === 'index.php')
    ? 'background-color:#ec5a95; color:#ffffff; border-radius:12px;'
    : '';

$productStyle = in_array($currentPage, ['product.php', 'edit_product.php'], true)
    ? 'background-color:#ec5a95; color:#ffffff; border-radius:12px;'
    : '';

$orderStyle = in_array($currentPage, ['orders.php', 'order-details.php'], true)
    ? 'background-color:#ec5a95; color:#ffffff; border-radius:12px;'
    : '';

$categoryStyle = ($currentPage === 'category.php')
    ? 'background-color:#ec5a95; color:#ffffff; border-radius:12px;'
    : '';

$settingsStyle = ($currentPage === 'settings.php')
    ? 'background-color:#ec5a95; color:#ffffff; border-radius:12px;'
    : '';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 col-sm-12 sidebar">

            <span class="heart">♥ Darlings</span>

            <nav>
                <a href="index.php"
                   class="<?= $currentPage === 'index.php' ? 'active' : '' ?>"
                   style="<?= $dashboardStyle ?>">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>

                <a href="product.php"
                   class="<?= in_array($currentPage, ['product.php', 'edit_product.php'], true) ? 'active' : '' ?>"
                   style="<?= $productStyle ?>">
                    <i class="fa-solid fa-bag-shopping"></i> Products
                </a>

                <a href="orders.php"
                   class="<?= in_array($currentPage, ['orders.php', 'order-details.php'], true) ? 'active' : '' ?>"
                   style="<?= $orderStyle ?>">
                    <i class="fa-solid fa-box"></i> Orders
                </a>

<li>
    <a href="customers.php">
        <i class="fas fa-users"></i>
        <span>Customers</span>
    </a>
</li>

                <a href="category.php"
                   class="<?= $currentPage === 'category.php' ? 'active' : '' ?>"
                   style="<?= $categoryStyle ?>">
                    <i class="fa-solid fa-tags"></i> Category
                </a>

                <a href="settings.php"
   class="<?= $currentPage === 'settings.php' ? 'active' : '' ?>"
   style="<?= $settingsStyle ?>">
    <i class="fa-solid fa-gear"></i> Settings
</a>
            </nav>

        </div>
  