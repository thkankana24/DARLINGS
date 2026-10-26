<?php
include 'PAGES/auth_check.php';
require_once 'sql/config.php';

$message = '';
$messageType = 'success';

/* ---------- ADD CATEGORY ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '') {
        $message = 'Please enter a category name.';
        $messageType = 'error';
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO categories (category_name, description)
             VALUES (?, ?)"
        );

        mysqli_stmt_bind_param($stmt, 'ss', $name, $description);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: category.php?msg=added');
            exit;
        } else {
            $message = mysqli_stmt_errno($stmt) == 1062
                ? 'This category already exists.'
                : 'Unable to add the category. Please try again.';
            $messageType = 'error';
        }

        mysqli_stmt_close($stmt);
    }
}

/* ---------- DELETE CATEGORY ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $categoryId = (int)($_POST['category_id'] ?? 0);

    $stmt = mysqli_prepare(
        $conn,
        "SELECT COUNT(p.id) AS total
         FROM categories c
         LEFT JOIN products_admin p
         ON LOWER(TRIM(p.category)) = LOWER(TRIM(c.category_name))
         WHERE c.id = ?
         GROUP BY c.id"
    );

    mysqli_stmt_bind_param($stmt, 'i', $categoryId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row && (int)$row['total'] === 0) {
        $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $categoryId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: category.php?msg=deleted');
        exit;
    } else {
        $message = 'This category has products assigned to it. Remove or reassign those products first.';
        $messageType = 'error';
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') {
        $message = 'Category added successfully!';
    } elseif ($_GET['msg'] === 'deleted') {
        $message = 'Category deleted successfully!';
    }
}

/* ---------- HELPERS ---------- */
function categoryIcon($name) {
    $name = strtolower($name);

    if (strpos($name, 'dress') !== false) return '👗';
    if (strpos($name, 'top') !== false || strpos($name, 'shirt') !== false) return '👚';
    if (strpos($name, 'jean') !== false || strpos($name, 'pant') !== false) return '👖';
    if (strpos($name, 'skirt') !== false) return '🩱';
    if (strpos($name, 'footwear') !== false || strpos($name, 'shoe') !== false) return '👠';
    if (strpos($name, 'bag') !== false || strpos($name, 'purse') !== false) return '👜';
    if (strpos($name, 'jewel') !== false) return '💎';
    if (strpos($name, 'accessor') !== false) return '🕶️';
    if (strpos($name, 'outerwear') !== false || strpos($name, 'jacket') !== false) return '🧥';
    if (strpos($name, 'activewear') !== false) return '🏃‍♀️';

    return '🛍️';
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function rupees($amount) {
    return '₹' . number_format((float)$amount, 2);
}

/* ---------- OPEN ONE CATEGORY (category.php?view=ID) ---------- */
$viewCategory = null;
$products = [];
$inStockCount = 0;

if (isset($_GET['view'])) {
    $viewId = (int)$_GET['view'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, category_name, description FROM categories WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, 'i', $viewId);
    mysqli_stmt_execute($stmt);
    $viewCategory = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if (!$viewCategory) {
        header('Location: category.php');
        exit;
    }

    // Products whose category matches this category's name
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, product_name, category, price, stock, size,
                discount, description, product_image
         FROM products_admin
         WHERE LOWER(TRIM(category)) = LOWER(TRIM(?))
         ORDER BY id DESC"
    );
    mysqli_stmt_bind_param($stmt, 's', $viewCategory['category_name']);
    mysqli_stmt_execute($stmt);
    $productResult = mysqli_stmt_get_result($stmt);

    while ($product = mysqli_fetch_assoc($productResult)) {
        $product['price'] = (float)$product['price'];
        $product['stock'] = (int)$product['stock'];
        $product['discount'] = (float)$product['discount'];

        // Discount is treated as a percentage
        $product['final_price'] = $product['discount'] > 0
            ? $product['price'] - ($product['price'] * $product['discount'] / 100)
            : $product['price'];

        if ($product['stock'] > 0) {
            $inStockCount++;
        }

        $products[] = $product;
    }
    mysqli_stmt_close($stmt);
}

/* ---------- ALL CATEGORIES (list view) ---------- */
$categories = [];

if (!$viewCategory) {
    $categoryQuery = mysqli_query(
        $conn,
        "SELECT c.id, c.category_name, c.description, c.created_at,
                COUNT(p.id) AS product_count
         FROM categories c
         LEFT JOIN products_admin p
         ON LOWER(TRIM(p.category)) = LOWER(TRIM(c.category_name))
         GROUP BY c.id, c.category_name, c.description, c.created_at
         ORDER BY c.category_name ASC"
    );

    if ($categoryQuery) {
        while ($category = mysqli_fetch_assoc($categoryQuery)) {
            $categories[] = $category;
        }
    }
}

include 'PAGES/header.php';
include 'PAGES/sidebar.php';
include 'PAGES/top-header.php';
?>
<link rel="stylesheet" href="css_file/category.css">

<?php if ($viewCategory): ?>
<!-- =====================================================
     SINGLE CATEGORY VIEW: products and their details
     ===================================================== -->
<main class="category-page">

    <a class="back-link" href="category.php">&larr; Back to categories</a>

    <div class="category-heading">
        <div>
            <span class="category-eyebrow">DARLINGS / COLLECTIONS</span>
            <h1>
                <?= categoryIcon($viewCategory['category_name']) ?>
                <?= e($viewCategory['category_name']) ?>
            </h1>
            <p><?= e($viewCategory['description'] ?: 'Explore this fashion collection.') ?></p>
        </div>

        <a class="add-category-btn" href="product.php">
            <span>＋</span> Add Product
        </a>
    </div>

    <div class="view-stats">
        <div class="stat-box">
            <strong><?= count($products) ?></strong>
            <span>Products</span>
        </div>
        <div class="stat-box">
            <strong><?= $inStockCount ?></strong>
            <span>In stock</span>
        </div>
        <div class="stat-box">
            <strong><?= count($products) - $inStockCount ?></strong>
            <span>Out of stock</span>
        </div>
    </div>

    <?php if (count($products) > 0): ?>

        <div class="category-toolbar">
            <div>
                <h2>Products in <?= e($viewCategory['category_name']) ?></h2>
                <p>Click Edit to change a product's details.</p>
            </div>

            <div class="category-search">
                <span>⌕</span>
                <input type="text" id="productSearch" placeholder="Search products...">
            </div>
        </div>

        <div class="product-grid" id="productGrid">

            <?php foreach ($products as $product): ?>
                <?php
                $image = trim((string)($product['product_image'] ?? ''));
                $imageUrl = $image !== ''
                    ? 'upload/' . rawurlencode(basename(str_replace('\\', '/', $image)))
                    : '';

                $sizes = array_filter(array_map('trim', explode(',', (string)$product['size'])));

                if ($product['stock'] < 1) {
                    $stockClass = 'out';
                    $stockText  = 'Out of stock';
                } elseif ($product['stock'] <= 5) {
                    $stockClass = 'low';
                    $stockText  = 'Only ' . $product['stock'] . ' left';
                } else {
                    $stockClass = 'in';
                    $stockText  = $product['stock'] . ' in stock';
                }
                ?>

                <article class="product-card"
                         data-name="<?= e(strtolower($product['product_name'])) ?>">

                    <div class="product-card-image">
                        <?php if ($imageUrl !== ''): ?>
                            <img src="<?= e($imageUrl) ?>"
                                 alt="<?= e($product['product_name']) ?>"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <div class="product-no-image" style="display:none;">Image unavailable</div>
                        <?php else: ?>
                            <div class="product-no-image">No image</div>
                        <?php endif; ?>

                        <?php if ($product['discount'] > 0): ?>
                            <span class="product-discount-badge">
                                <?= rtrim(rtrim(number_format($product['discount'], 2), '0'), '.') ?>% OFF
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="product-card-body">
                        <div class="product-card-title">
                            <h3><?= e($product['product_name']) ?></h3>
                            <span class="product-id">#<?= (int)$product['id'] ?></span>
                        </div>

                        <div class="product-price">
                            <strong><?= e(rupees($product['final_price'])) ?></strong>
                            <?php if ($product['discount'] > 0): ?>
                                <del><?= e(rupees($product['price'])) ?></del>
                            <?php endif; ?>
                        </div>

                        <span class="stock-badge <?= $stockClass ?>"><?= e($stockText) ?></span>

                        <?php if (!empty($sizes)): ?>
                            <div class="size-list">
                                <?php foreach ($sizes as $size): ?>
                                    <span><?= e($size) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <p class="product-description">
                            <?= e($product['description'] !== '' ? $product['description'] : 'No description added.') ?>
                        </p>

                        <a class="view-products-btn" href="edit_product.php?id=<?= (int)$product['id'] ?>">
                            Edit Product <span>→</span>
                        </a>
                    </div>
                </article>

            <?php endforeach; ?>

            <div class="category-no-results" id="productNoResults">
                No matching products found.
            </div>
        </div>

    <?php else: ?>

        <div class="category-empty">
            <span>🛍️</span>
            <h3>No products in this category yet</h3>
            <p>Add a product and choose “<?= e($viewCategory['category_name']) ?>” as its category.</p>
        </div>

    <?php endif; ?>
</main>

<script>
const productSearch = document.getElementById('productSearch');

if (productSearch) {
    productSearch.addEventListener('input', function () {
        const value = this.value.toLowerCase().trim();
        let visible = 0;

        document.querySelectorAll('.product-card').forEach(function (card) {
            const show = (card.dataset.name || '').includes(value);
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('productNoResults').style.display =
            visible === 0 ? 'block' : 'none';
    });
}
</script>

<?php else: ?>
<!-- =====================================================
     ALL CATEGORIES VIEW
     ===================================================== -->
<main class="category-page">

    <div class="category-heading">
        <div>
            <span class="category-eyebrow">DARLINGS / COLLECTIONS</span>
            <h1>Categories <span>♡</span></h1>
            <p>Organise your store's female fashion collections in one place.</p>
        </div>

        <button type="button" class="add-category-btn" onclick="openCategoryModal()">
            <span>＋</span> Add Category
        </button>
    </div>

    <?php if ($message !== ''): ?>
        <div class="category-message <?= e($messageType) ?>">
            <?= e($message) ?>
        </div>
    <?php endif; ?>

    <div class="category-toolbar">
        <div>
            <h2>Your Collections</h2>
            <p>Browse and manage your fashion categories.</p>
        </div>

        <div class="category-search">
            <span>⌕</span>
            <input type="text" id="categorySearch" placeholder="Search categories...">
        </div>
    </div>

    <div class="category-count">
        <strong><?= count($categories) ?></strong>
        <span>categories in your store</span>
    </div>

    <div class="category-grid" id="categoryGrid">

        <?php if (count($categories) > 0): ?>

            <?php foreach ($categories as $category): ?>
                <article class="category-card"
                         data-name="<?= e(strtolower($category['category_name'])) ?>">

                    <div class="category-card-top">
                        <div class="category-art">
                            <?= categoryIcon($category['category_name']) ?>
                        </div>

                        <span class="category-number">
                            <?= (int)$category['product_count'] ?> products
                        </span>
                    </div>

                    <div class="category-card-info">
                        <h3><?= e($category['category_name']) ?></h3>

                        <p>
                            <?= e($category['description'] ?: 'Explore this fashion collection.') ?>
                        </p>

                        <div class="category-card-actions">
                            <a class="view-products-btn"
                               href="category.php?view=<?= (int)$category['id'] ?>">
                                View Products <span>→</span>
                            </a>

                            <?php if ((int)$category['product_count'] === 0): ?>
                                <form method="POST" onsubmit="return confirm('Delete this category?');">
                                    <input type="hidden" name="category_id"
                                           value="<?= (int)$category['id'] ?>">
                                    <button type="submit" name="delete_category"
                                            class="delete-category-btn"
                                            title="Delete category">
                                        🗑
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="category-empty">
                <span>🛍️</span>
                <h3>No categories yet</h3>
                <p>Click “Add Category” to create your first collection.</p>
            </div>
        <?php endif; ?>

        <div class="category-no-results" id="categoryNoResults">
            No matching categories found.
        </div>

    </div>
</main>

<!-- ADD CATEGORY MODAL -->
<div class="category-modal-overlay" id="categoryModal">
    <div class="category-modal">

        <button type="button" class="category-modal-close" onclick="closeCategoryModal()">×</button>

        <div class="modal-icon">✿</div>
        <h2>Add New Category</h2>
        <p>Create a new collection for your Darlings store.</p>

        <form method="POST">
            <label for="category_name">Category Name</label>
            <input type="text" id="category_name" name="category_name"
                   placeholder="e.g. Party Dresses" maxlength="100" required>

            <label for="description">Description</label>
            <textarea id="description" name="description"
                      placeholder="Write a short description..." maxlength="255"></textarea>

            <button type="submit" name="add_category" class="save-category-btn">
                Save Category
            </button>
        </form>
    </div>
</div>

<script>
function openCategoryModal() {
    document.getElementById('categoryModal').classList.add('show');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('show');
}

document.getElementById('categoryModal').addEventListener('click', function (event) {
    if (event.target === this) {
        closeCategoryModal();
    }
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') closeCategoryModal();
});

/* LIVE CATEGORY SEARCH */
document.getElementById('categorySearch').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase().trim();
    let visibleCount = 0;

    document.querySelectorAll('.category-card').forEach(function (card) {
        const visible = (card.dataset.name || '').includes(searchValue);
        card.style.display = visible ? '' : 'none';
        if (visible) visibleCount++;
    });

    document.getElementById('categoryNoResults').style.display =
        visibleCount === 0 ? 'block' : 'none';
});
</script>
<?php endif; ?>

<?php include 'PAGES/footer.php'; ?>